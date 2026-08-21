<?php

namespace Drenso\Shared\FeatureFlags;

interface FeatureFlagsInterface
{
  public function getFlagValue(string $flag): bool;

  /** @return string[] */
  public function getConfiguredFlags(): array;
}
