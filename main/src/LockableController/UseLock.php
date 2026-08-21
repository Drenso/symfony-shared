<?php

namespace Drenso\Shared\LockableController;

use Attribute;

#[Attribute(Attribute::IS_REPEATABLE|Attribute::TARGET_CLASS|Attribute::TARGET_METHOD)]
class UseLock
{
  public function __construct(public readonly string $lockName)
  {
  }
}
