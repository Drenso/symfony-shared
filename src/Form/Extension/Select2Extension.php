<?php

namespace Drenso\Shared\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class Select2Extension
 *
 * This class provides a Select2 form extension, to be able to easily set
 * select 2 options on the form
 *
 * @author BobV
 */
class Select2Extension extends AbstractTypeExtension
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder->setAttribute('select2', $options['select2']);
  }

  public function buildView(FormView $view, FormInterface $form, array $options)
  {
    $view->vars['select2'] = $options['select2'];
    $view->vars['allowClear'] = !$options['required'];
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults([
        'select2' => false,
    ]);

    $resolver->setAllowedTypes('select2', ['bool']);
  }

  public static function getExtendedTypes(): iterable
  {
    return [ChoiceType::class];
  }

  public function getExtendedType()
  {
    return self::getExtendedTypes()[0];
  }
}
