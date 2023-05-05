<?php

namespace Drenso\Shared\FeatureFlags;

interface FeatureFlagsInterface
{
  public function getFlagValue(string $flag): bool;
}
