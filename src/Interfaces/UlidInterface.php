<?php

namespace Drenso\Shared\Interfaces;

use Symfony\Component\Uid\Ulid;

interface UlidInterface
{
  public function getUlid(): Ulid;

  public function getUlidAsString(): string;
}
