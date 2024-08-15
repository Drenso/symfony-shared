<?php

namespace Drenso\Shared\Exception\NullGuard;

use RuntimeException;

class MustNotBeNullException extends RuntimeException
{
  public function __construct(?string $message = null)
  {
    parent::__construct($message ?? 'Value must not be null');
  }
}
