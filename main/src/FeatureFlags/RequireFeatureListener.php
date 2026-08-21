<?php

namespace Drenso\Shared\FeatureFlags;

use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/** Handles the RequireFeature attribute on controllers. */
class RequireFeatureListener
{
  public function __construct(private readonly FeatureFlagsInterface $featureFlags)
  {
  }

  public function __invoke(ControllerArgumentsEvent $event): void
  {
    foreach ($event->getAttributes(RequireFeature::class) as $attribute) {
      /** @var RequireFeature $attribute */
      if (!$this->featureFlags->getFlagValue($attribute->flag)) {
        throw new NotFoundHttpException(sprintf('Feature disabled (%s)', $attribute->flag));
      }
    }
  }
}
