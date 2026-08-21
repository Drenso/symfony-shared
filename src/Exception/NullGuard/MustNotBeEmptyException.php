<?php

namespace Drenso\Shared\Exception\NullGuard;

use RuntimeException;

class MustNotBeEmptyException extends RuntimeException
{
  public function __construct(?string $message = null)
  {
    parent::__construct($message ?? 'Value must not be empty');
  }
}
