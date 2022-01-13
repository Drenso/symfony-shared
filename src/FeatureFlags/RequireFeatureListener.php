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

    /** @var $annotations RequireFeature[] */
    if (!$annotations = $request->attributes->get('_drenso_require_feature')) {
      return;
    }

    foreach ($annotations as $annotation) {
      if (!$this->featureFlags->getFlagValue($annotation->flag)) {
        throw new NotFoundHttpException(sprintf('Feature disabled (%s)', $annotation->flag));
      }
    }
  }

  public static function getSubscribedEvents(): array
  {
    return [KernelEvents::CONTROLLER_ARGUMENTS => 'onKernelControllerArguments'];
  }
}
