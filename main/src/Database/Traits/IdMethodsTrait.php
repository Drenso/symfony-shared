<?php

namespace Drenso\Shared\Database\Traits;

use Drenso\Shared\Exception\NullGuard\IdRequiredException;

/** @phpstan-ignore trait.unused */
trait IdMethodsTrait
{
  public function getId(): ?int
  {
    return $this->id;
  }

  public function getNonNullId(): int
  {
    return $this->id ?? throw new IdRequiredException();
  }
}
