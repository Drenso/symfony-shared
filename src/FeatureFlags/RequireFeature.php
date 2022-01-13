<?php

namespace Drenso\Shared\FeatureFlags;

use Attribute;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationAnnotation;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
class RequireFeature extends ConfigurationAnnotation
{
  public function __construct(public readonly string $flag)
  {
    parent::__construct([]);
  }

  public function getAliasName(): string
  {
    return 'drenso_require_feature';
  }

  public function allowArray(): bool
  {
    return true;
  }
}
