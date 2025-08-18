<?php

namespace Drenso\Shared\Database\Traits;

use Symfony\Component\Uid\NilUlid;
use Symfony\Component\Uid\Ulid;

/** @phpstan-ignore trait.unused */
trait UlidMethodsTrait
{
  public function getUlid(): Ulid
  {
    return $this->ulid ?? new NilUlid();
  }

  public function getUlidAsString(): string
  {
    return ($this->ulid ?? new NilUlid())->toBase32();
  }
}
