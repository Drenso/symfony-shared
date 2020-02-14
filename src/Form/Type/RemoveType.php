<?php

namespace Drenso\Shared\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class RemoveType
 *
 * @author BobV
 */
class RemoveType extends AbstractType
{

  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
        ->add('_remove', SubmitType::class, array(
            'label' => $options['remove_label'],
            'icon'  => $options['remove_icon'],
            'attr'  => array(
                'class' => $options['remove_btn_class'],
            ),
        ))
        ->add('_cancel', ButtonUrlType::class, array(
            'label'        => $options['cancel_label'],
            'icon'         => $options['cancel_icon'],
            'route'        => $options['cancel_route'],
            'route_params' => $options['cancel_route_params'],
            'attr'         => [
                'class' => $options['cancel_btn_class'],
            ],
        ));
  }

  /**
   * Check whether the "remove" button is clicked
   *
   * @param FormInterface $form
   *
   * @return bool
   */
  public static function isRemoveClicked(FormInterface $form)
  {
    assert($form instanceof Form);
    $clickedButton = $form->getClickedButton();

    if ($form->isSubmitted()
        && $clickedButton instanceof SubmitButton
        && $clickedButton->getName() === '_remove'
    ) {
      return true;
    }

    return false;
  }

  /**
   * @param OptionsResolver $resolver
   */
  public function configureOptions(OptionsResolver $resolver)
  {

    $resolver->setDefaults(array(
        'mapped' => false,

        'remove_label'     => 'form.confirm-remove',
        'remove_icon'      => 'fa-check',
        'remove_btn_class' => 'btn-outline-danger',

        'cancel_label'        => 'form.cancel',
        'cancel_icon'         => 'fa-times',
        'cancel_route_params' => array(),
        'cancel_btn_class'    => 'btn-outline-dark',
    ));

    $resolver->setRequired('cancel_route');

    $resolver->setAllowedTypes('remove_label', 'string');
    $resolver->setAllowedTypes('remove_icon', 'string');
    $resolver->setAllowedTypes('remove_btn_class', 'string');
    $resolver->setAllowedTypes('cancel_label', 'string');
    $resolver->setAllowedTypes('cancel_route', 'string');
    $resolver->setAllowedTypes('cancel_route_params', 'array');
    $resolver->setAllowedTypes('cancel_btn_class', 'string');
  }
}
