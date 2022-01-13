<?php

namespace Drenso\Shared\FeatureFlags;

use RuntimeException;

class FeatureFlagInvalidTypeException extends RuntimeException
{
  public function __construct(string $flag, mixed $value)
  {
    parent::__construct(sprintf(
        'The configured value for feature flag "%s" is expected to be a boolean, but "%s" found', $flag, gettype($value)));
  }
}
