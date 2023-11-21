<?php

namespace Drenso\Shared\FeatureFlags;

use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Handles the RequireFeature attribute on controllers.
 */
class RequireFeatureListener
{
  public function __construct(private readonly FeatureFlagsInterface $featureFlags)
  {
  }

  public function __invoke(ControllerArgumentsEvent $event): void
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
}
