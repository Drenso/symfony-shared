<?php

namespace Drenso\Shared\LockableController;

use Attribute;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationInterface;

#[Attribute(Attribute::IS_REPEATABLE|Attribute::TARGET_CLASS|Attribute::TARGET_FUNCTION)]
class UseLock implements ConfigurationInterface
{
  public function getAliasName(): string
  {
    return 'drenso_use_lock';
  }

  public function allowArray(): bool
  {
    return true;
  }
}
