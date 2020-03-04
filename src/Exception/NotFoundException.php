<?php

namespace App\Exception;

use RuntimeException;

class NotFoundException extends RuntimeException
{
  public function __construct(string $type, int $id)
  {
    parent::__construct(sprintf('Could not find %s with id %d', $type, $id));
  }

}
