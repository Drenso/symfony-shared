<?php

namespace Drenso\Shared\Form\Extension;

use Drenso\Shared\Form\Type\Select2EntitySearchType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * This class provides a Select2 form extension, to be able to easily set
 * select 2 options on the form.
 */
class Select2Extension extends AbstractTypeExtension
{
  public function __construct(private readonly ?TranslatorInterface $translator)
  {
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder->setAttribute('select2', $options['select2']);
  }

  public function buildView(FormView $view, FormInterface $form, array $options): void
  {
    $view->vars['select2'] = $options['select2'];

    if ($options['select2']) {
      // Create options
      $select2Options = [
        'width'      => '100%',
        'theme'      => $options['select2_theme'],
        'allowClear' => !$options['required'],
        'multiple'   => $options['multiple'],
      ];

      // Determine the placeholder
      if ($options['placeholder']) {
        $select2Options['placeholder'] = $options['translation_domain'] === false || !$this->translator
            ? $options['placeholder']
            : $this->translator->trans($options['placeholder'], [], $options['translation_domain']);
      } elseif (!$options['required']) {
        // Add default placeholder
        $select2Options['placeholder'] = !$this->translator
            ? 'None'
            : $this->translator->trans('form.select2-placeholder', [], 'drenso_shared');
      }

      // Merge explicit options, which override everything else
      $view->vars['select2_options'] = array_merge($select2Options, $options['select2_options']);
    }
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver
      ->setDefaults([
        'select2'         => false,
        'select2_theme'   => 'bootstrap',
        'select2_options' => [],
      ])
      ->setAllowedTypes('select2', ['bool'])
      ->setAllowedTypes('select2_theme', ['string'])
      ->setAllowedTypes('select2_options', ['array']);
  }

  public static function getExtendedTypes(): iterable
  {
    return [ChoiceType::class, Select2EntitySearchType::class];
  }
}
