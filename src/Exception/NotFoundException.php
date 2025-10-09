<?php

namespace Drenso\Shared\Exception;

use RuntimeException;
use Symfony\Component\Uid\Ulid;

class NotFoundException extends RuntimeException
{
  public function __construct(string $type, int|Ulid $id)
  {
    $idString = $id instanceof Ulid ? $id->toBase32() : sprintf('%d', $id);

    parent::__construct("Could not find $type with id $idString");
  }
}
