<?php

namespace Drenso\Shared\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ButtonExtension extends AbstractTypeExtension
{

  /**
   * @param FormView      $view
   * @param FormInterface $form
   * @param array         $options
   */
  public function buildView(FormView $view, FormInterface $form, array $options)
  {
    $view->vars['icon'] = $options['icon'] === NULL ? NULL
        : sprintf('%s %s', $options['icon_prefix'], $options['icon']);
  }

  /**
   * @param OptionsResolver $resolver
   */
  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults([
        'icon'        => NULL,
        'icon_prefix' => 'fas fa-fw',
    ]);

    $resolver->setAllowedTypes('icon', ['string', 'null']);
    $resolver->setAllowedTypes('icon_prefix', ['string']);
  }

  public static function getExtendedTypes(): iterable
  {
    return [ButtonType::class];
  }

  public function getExtendedType()
  {
    return self::getExtendedTypes()[0];
  }
}
