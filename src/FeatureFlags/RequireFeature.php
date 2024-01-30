<?php

namespace Drenso\Shared\FeatureFlags;

use Attribute;

#[Attribute(Attribute::IS_REPEATABLE|Attribute::TARGET_CLASS|Attribute::TARGET_METHOD)]
class RequireFeature
{
  public function __construct(public readonly string $flag)
  {
  }
}
