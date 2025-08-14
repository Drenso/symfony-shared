<?php

namespace Drenso\Shared\Interfaces;

use Symfony\Component\Uid\Ulid;

interface UlidInterface
{
  /**
   * Retrieve the object ULID as string.
   * This can be an empty ULID, but should be a generated one uniquely identifying the object.
   */
  public function getUlid(): Ulid;

  /** Retrieve the object ULID as string using its base32 representation. */
  public function getUlidAsString(): string;
}
