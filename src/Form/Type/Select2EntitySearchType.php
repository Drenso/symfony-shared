<?php

namespace Drenso\Shared\Form\Type;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Persistence\ManagerRegistry;
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
  /**
   * @var PropertyAccessorInterface
   */
  private $propertyAccessor;
  /**
   * @var ManagerRegistry
   */
  private $registry;

  public function __construct(ManagerRegistry $registry, PropertyAccessorInterface $propertyAccessor)
  {
    $this->registry         = $registry;
    $this->propertyAccessor = $propertyAccessor;
  }

  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $repository = $this->registry->getRepository($options['class']);

    $builder
        ->addViewTransformer(new CallbackTransformer(
            function ($normData) use ($options) {
              if (NULL === $normData) {
                return $options['multiple'] ? [] : NULL;
              }

              if ($normData instanceof Collection) {
                $normData = $normData->toArray();
              } else if (!is_iterable($normData)) {
                $normData = [$normData];
              }

              $normData = array_map(function ($item) use ($options) {
                return [
                    'value' => $this->propertyAccessor->getValue($item, 'id'),
                    'label' => $this->propertyAccessor->getValue($item, $options['choice_label']),
                ];
              }, $normData);

              if (!$options['multiple']) {
                return $normData[0];
              }

              return $normData;
            },
            function ($viewData) use ($repository, $options) {
              if (!$viewData) {
                return $options['multiple'] ? [] : NULL;
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

  public function buildView(FormView $view, FormInterface $form, array $options)
  {
    $view->vars['multiple'] = $options['multiple'];

    if ($options['multiple']) {
      // Add "[]" to the name in case a select tag with multiple options is
      // displayed. Otherwise only one of the selected options is sent in the
      // POST request.
      $view->vars['full_name'] .= '[]';
    }
  }

  public function configureOptions(OptionsResolver $resolver)
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
        ->addNormalizer('compound', function () {
          // Force non-compound
          return false;
        })
        ->addNormalizer('select2', function () {
          // Force the use of select 2
          return true;
        })
        ->addNormalizer('compound', function () {
          // Force non-compound
          return false;
        })
        ->addNormalizer('select2_options', function (Options $options, $value) {
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
