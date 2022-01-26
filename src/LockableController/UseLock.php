<?php

namespace Drenso\Shared\LockableController;

use Attribute;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationInterface;

#[Attribute(Attribute::IS_REPEATABLE|Attribute::TARGET_CLASS|Attribute::TARGET_METHOD)]
class UseLock implements ConfigurationInterface
{
  public function __construct(public readonly string $lockName)
  {
  }

  public function getAliasName(): string
  {
    return 'drenso_use_lock';
  }

  public function allowArray(): bool
  {
    return true;
  }
}
