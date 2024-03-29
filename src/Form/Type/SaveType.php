<?php

namespace Drenso\Shared\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SaveType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    // Add the save button if required
    if ($options['enable_save']) {
      $builder->add('_save', SubmitType::class, [
        'label'              => $options['save_label'],
        'translation_domain' => $options['save_translation_domain'],
        'icon'               => $options['save_icon'],
        'attr'               => [
          'class' => $options['save_btn_class'],
        ],
      ]);
    }

    // Add the save and list button if required
    if ($options['enable_save_and_list']) {
      $builder->add('_save_and_list', SubmitType::class, [
        'label'              => $options['save_and_list_label'],
        'translation_domain' => $options['save_and_list_translation_domain'],
        'icon'               => $options['save_and_list_icon'],
        'attr'               => [
          'class' => $options['save_and_list_btn_class'],
        ],
      ]);
    }

    // Add the list button if required
    if ($options['enable_list']) {
      $builder->add('_list', ButtonUrlType::class, [
        'label'              => $options['list_label'],
        'translation_domain' => $options['list_translation_domain'],
        'icon'               => $options['list_icon'],
        'route'              => $options['list_route'],
        'route_params'       => $options['list_route_params'],
        'attr'               => [
          'class' => $options['list_btn_class'],
        ],
      ]);
    }

    if ($options['enable_cancel']) {
      $builder->add('_cancel', ButtonUrlType::class, [
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
  }

  /** Check whether the "save and list" button is clicked. */
  public static function isListClicked(FormInterface $form): bool
  {
    assert($form instanceof Form);
    $clickedButton = $form->getClickedButton();
    if ($form->isSubmitted()
        && $clickedButton instanceof SubmitButton
        && $clickedButton->getName() === '_save_and_list'
    ) {
      return true;
    }

    return false;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'mapped' => false,

      'enable_save'             => true,
      'save_label'              => 'form.save',
      'save_translation_domain' => 'drenso_shared',
      'save_icon'               => 'fa-check',
      'save_btn_class'          => 'btn-outline-success',

      'enable_save_and_list'             => true,
      'save_and_list_label'              => 'form.save-and-list',
      'save_and_list_translation_domain' => 'drenso_shared',
      'save_and_list_icon'               => 'fa-check',
      'save_and_list_btn_class'          => 'btn-outline-success',

      'enable_list'             => false,
      'list_label'              => 'form.list',
      'list_translation_domain' => 'drenso_shared',
      'list_icon'               => 'fa-list',
      'list_route'              => null,
      'list_route_params'       => [],
      'list_btn_class'          => 'btn-outline-secondary',

      'enable_cancel'             => false,
      'cancel_label'              => 'form.cancel',
      'cancel_translation_domain' => 'drenso_shared',
      'cancel_icon'               => 'fa-times',
      'cancel_route'              => null,
      'cancel_route_params'       => [],
      'cancel_btn_class'          => 'btn-outline-danger',
    ]);

    $resolver->setAllowedTypes('enable_save', 'bool');
    $resolver->setAllowedTypes('save_label', 'string');
    $resolver->setAllowedTypes('save_translation_domain', 'string');
    $resolver->setAllowedTypes('save_icon', 'string');
    $resolver->setAllowedTypes('save_btn_class', 'string');

    $resolver->setAllowedTypes('enable_save_and_list', 'bool');
    $resolver->setAllowedTypes('save_and_list_label', 'string');
    $resolver->setAllowedTypes('save_and_list_translation_domain', 'string');
    $resolver->setAllowedTypes('save_and_list_icon', 'string');
    $resolver->setAllowedTypes('save_and_list_btn_class', 'string');

    $resolver->setAllowedTypes('enable_list', 'bool');
    $resolver->setAllowedTypes('list_label', 'string');
    $resolver->setAllowedTypes('list_translation_domain', 'string');
    $resolver->setAllowedTypes('list_icon', 'string');
    $resolver->setAllowedTypes('list_route', ['null', 'string']);
    $resolver->setAllowedTypes('list_route_params', 'array');
    $resolver->setAllowedTypes('list_btn_class', 'string');

    $resolver->setAllowedTypes('enable_cancel', 'bool');
    $resolver->setAllowedTypes('cancel_label', 'string');
    $resolver->setAllowedTypes('cancel_translation_domain', 'string');
    $resolver->setAllowedTypes('cancel_icon', 'string');
    $resolver->setAllowedTypes('cancel_route', ['null', 'string']);
    $resolver->setAllowedTypes('cancel_route_params', 'array');
    $resolver->setAllowedTypes('cancel_btn_class', 'string');

    $resolver->setNormalizer('list_route', function (Options $options, $value) {
      if ($options['enable_list'] === true && $value === null) {
        throw new MissingOptionsException('The option "list_route" is not set, while the list button is enabled.');
      }

      return $value;
    });
    $resolver->setNormalizer('cancel_route', function (Options $options, $value) {
      if ($options['enable_cancel'] === true && $value === null) {
        throw new MissingOptionsException('The option "cancel_route" is not set, while the cancel button is enabled.');
      }

      return $value;
    });
  }
}
