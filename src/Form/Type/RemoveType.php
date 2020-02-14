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
        ->add('_cancel', ButtonUrlType::class, array(
            'label'        => $options['cancel_label'],
            'icon'         => 'fa-times',
            'route'        => $options['cancel_route'],
            'route_params' => $options['cancel_route_params'],
            'attr'         => [
                'class' => 'btn-outline-dark',
            ],
        ))
        ->add('_remove', SubmitType::class, array(
            'label' => $options['remove_label'],
            'icon'  => 'fa-check',
            'attr'  => array(
                'class' => 'btn-outline-danger float-right',
            ),
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
        'mapped'              => false,
        'remove_label'        => 'form.confirm-remove',
        'cancel_label'        => 'form.cancel',
        'cancel_route_params' => array(),
    ));

    $resolver->setRequired('cancel_route');

    $resolver->setAllowedTypes('remove_label', 'string');
    $resolver->setAllowedTypes('cancel_label', 'string');
    $resolver->setAllowedTypes('cancel_route', 'string');
    $resolver->setAllowedTypes('cancel_route_params', 'array');
  }
}
