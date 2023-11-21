<?php

namespace Drenso\Shared\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * This class provides our extra form extension, such as:
 *  - hide_label
 *  - form_header.
 */
class FormExtension extends AbstractTypeExtension
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder->setAttribute('hide_label', $options['hide_label']);
    $builder->setAttribute('form_header', $options['form_header']);
  }

  public function buildView(FormView $view, FormInterface $form, array $options): void
  {
    $view->vars['hide_label']  = $options['hide_label'];
    $view->vars['form_header'] = $options['form_header'];
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'hide_label'  => false,
      'form_header' => null,
    ]);
    $resolver->setAllowedTypes('hide_label', ['bool']);
    $resolver->setAllowedTypes('form_header', ['null', 'string']);
  }

  public static function getExtendedTypes(): iterable
  {
    return [FormType::class];
  }
}
