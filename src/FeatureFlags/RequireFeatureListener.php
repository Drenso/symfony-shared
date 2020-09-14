<?php

namespace Drenso\Shared\FeatureFlags;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Handles the RequireFeature annotation on controllers.
 */
class RequireFeatureListener implements EventSubscriberInterface
{
  /**
   * @var FeatureFlags
   */
  private $featureFlags;

  public function __construct(FeatureFlags $featureFlags)
  {
    $this->featureFlags = $featureFlags;
  }

  public function onKernelControllerArguments(ControllerArgumentsEvent $event)
  {
    $request = $event->getRequest();

    /** @var $annotations RequireFeature[] */
    if (!$annotations = $request->attributes->get('_drenso_require_feature')) {
      return;
    }

    foreach ($annotations as $annotation) {
      $flag = $annotation->getFlag();
      if (!$this->featureFlags->getFlagValue($flag)) {
        throw new NotFoundHttpException(sprintf('Feature disabled (%s)', $flag));
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents()
  {
    return [KernelEvents::CONTROLLER_ARGUMENTS => 'onKernelControllerArguments'];
  }
}
