<?php

namespace Drenso\Shared\LockableController;

use Attribute;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationAnnotation;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_CLASS | Attribute::TARGET_FUNCTION)]
class UseLock extends ConfigurationAnnotation
{
  public function __construct(public readonly string $lockName)
  {
    parent::__construct([]);
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
