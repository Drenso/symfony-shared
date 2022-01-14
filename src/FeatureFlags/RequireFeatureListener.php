<?php

namespace Drenso\Shared\FeatureFlags;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Handles the RequireFeature attribute on controllers.
 */
class RequireFeatureListener implements EventSubscriberInterface
{
  public function __construct(private FeatureFlags $featureFlags)
  {
  }

  public function onKernelControllerArguments(ControllerArgumentsEvent $event)
  {
    $request = $event->getRequest();

    /** @var $attributes RequireFeature[] */
    if (!$attributes = $request->attributes->get('_drenso_require_feature')) {
      return;
    }

    foreach ($attributes as $attribute) {
      if (!$this->featureFlags->getFlagValue($attribute->flag)) {
        throw new NotFoundHttpException(sprintf('Feature disabled (%s)', $attribute->flag));
      }
    }
  }

  public static function getSubscribedEvents(): array
  {
    return [KernelEvents::CONTROLLER_ARGUMENTS => 'onKernelControllerArguments'];
  }
}
