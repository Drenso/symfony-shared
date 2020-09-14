<?php

namespace Drenso\Shared\FeatureFlags;

use RuntimeException;

class FeatureFlagNotConfiguredException extends RuntimeException
{
  public function __construct(string $flag)
  {
    parent::__construct(sprintf('Requested flag "%s" is not configured', $flag));
  }
}
