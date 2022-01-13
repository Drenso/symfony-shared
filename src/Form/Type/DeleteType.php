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
 * Class DeleteType
 *
 * @author BobV
 */
class DeleteType extends AbstractType
{

  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
        ->add('_remove', SubmitType::class, [
            'label'              => $options['delete_label'],
            'translation_domain' => $options['delete_translation_domain'],
            'icon'               => $options['delete_icon'],
            'attr'               => [
                'class' => $options['delete_btn_class'],
            ],
        ])
        ->add('_cancel', ButtonUrlType::class, [
            'label'              => $options['cancel_label'],
            'translation_domain' => $options['cancel_translation_domain'],
            'icon'               => $options['cancel_icon'],
            'route'              => $options['cancel_route'],
            'route_params'       => $options['cancel_route_params'],
            'attr'               => [
                'class' => $options['cancel_btn_class'],
            ],
        ]);
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

    $resolver->setDefaults([
        'mapped' => false,

        'delete_label'              => 'form.confirm-delete',
        'delete_translation_domain' => 'drenso_shared',
        'delete_icon'               => 'fa-check',
        'delete_btn_class'          => 'btn-outline-danger',

        'cancel_label'              => 'form.cancel',
        'cancel_translation_domain' => 'drenso_shared',
        'cancel_icon'               => 'fa-times',
        'cancel_route_params'       => [],
        'cancel_btn_class'          => 'btn-outline-dark',
    ]);

    $resolver->setRequired('cancel_route');

    $resolver->setAllowedTypes('delete_label', 'string');
    $resolver->setAllowedTypes('delete_translation_domain', 'string');
    $resolver->setAllowedTypes('delete_icon', 'string');
    $resolver->setAllowedTypes('delete_btn_class', 'string');
    $resolver->setAllowedTypes('cancel_label', 'string');
    $resolver->setAllowedTypes('cancel_translation_domain', 'string');
    $resolver->setAllowedTypes('cancel_route', 'string');
    $resolver->setAllowedTypes('cancel_route_params', 'array');
    $resolver->setAllowedTypes('cancel_btn_class', 'string');
  }
}
