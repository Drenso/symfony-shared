<?php

namespace Drenso\Shared\Form\Type;

use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

/**
 * The Select2EntitySearch type extends the Entity type, but does not load any choices by default.
 *
 * It maps the data correctly for the inputs
 */
class Select2EntitySearchType extends AbstractType
{
  public function __construct(
    private readonly ManagerRegistry $registry,
    private readonly PropertyAccessorInterface $propertyAccessor)
  {
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $repository = $this->registry->getRepository($options['class']);

    $builder
      ->addViewTransformer(new CallbackTransformer(
        function (mixed $normData) use ($options): mixed {
          if (null === $normData) {
            return $options['multiple'] ? [] : null;
          }

          if ($normData instanceof Collection) {
            $normData = $normData->toArray();
          } elseif (!is_iterable($normData)) {
            $normData = [$normData];
          }

          $normData = array_map(fn (object|array $item): array => [
            'value' => $this->propertyAccessor->getValue($item, 'id'),
            'label' => $this->propertyAccessor->getValue($item, $options['choice_label']),
          ], $normData);

          if (!$options['multiple']) {
            return $normData[0];
          }

          return $normData;
        },
        function (mixed $viewData) use ($repository, $options): mixed {
          if (!$viewData) {
            return $options['multiple'] ? [] : null;
          }

          if ($options['multiple']) {
            if (!is_array($viewData)) {
              throw new TransformationFailedException('Array data is required for multiple option');
            }

            $result = [];
            foreach ($viewData as $itemId) {
              if (!$item = $repository->find($itemId)) {
                throw new TransformationFailedException('Could not convert data into entity');
              }

              $result[] = $item;
            }

            return $result;
          }

          if (!$item = $repository->find($viewData)) {
            throw new TransformationFailedException('Could not convert data into entity');
          }

          return $item;
        }
      ));
  }

  public function buildView(FormView $view, FormInterface $form, array $options): void
  {
    $view->vars['multiple'] = $options['multiple'];

    if ($options['multiple']) {
      // Add "[]" to the name in case a select tag with multiple options is
      // displayed. Otherwise only one of the selected options is sent in the
      // POST request.
      $view->vars['full_name'] .= '[]';
    }
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver
      ->setDefaults([
        'multiple'        => false,
        'select2'         => true,
        'select2_options' => [],
        'search_delay'    => 250,
      ])
      ->setAllowedTypes('multiple', 'bool')
      ->setAllowedTypes('select2', 'bool')
      ->setAllowedTypes('select2_options', 'array')
      ->setAllowedTypes('search_delay', 'int')
      ->setRequired([
        'class',
        'search_url',
      ])
      ->setAllowedTypes('class', 'string')
      ->setAllowedTypes('search_url', 'string')
      ->addNormalizer('compound', fn (): bool => false) // Force non-compound
      ->addNormalizer('select2', fn (): bool => true) // Force the use of select 2
      ->addNormalizer('select2_options', function (Options $options, array $value): array {
        if (!array_key_exists('ajax', $value)) {
          $value['ajax'] = [];
        }

        if (!array_key_exists('delay', $value['ajax'])) {
          $value['ajax']['delay'] = $options['search_delay'];
        }

        if (!array_key_exists('url', $value['ajax'])) {
          $value['ajax']['url'] = $options['search_url'];
        }

        if (!array_key_exists('minimumInputLength', $value)) {
          $value['minimumInputLength'] = 1;
        }

        return $value;
      });
  }
}
