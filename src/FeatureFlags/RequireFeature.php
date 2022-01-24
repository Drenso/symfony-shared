<?php

namespace Drenso\Shared\FeatureFlags;

use Attribute;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationInterface;

#[Attribute(Attribute::IS_REPEATABLE|Attribute::TARGET_CLASS|Attribute::TARGET_METHOD)]
class RequireFeature implements ConfigurationInterface
{
  public function getAliasName(): string
  {
    return 'drenso_require_feature';
  }

  public function allowArray(): bool
  {
    return true;
  }
}
