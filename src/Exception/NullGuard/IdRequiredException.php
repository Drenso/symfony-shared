<?php

namespace Drenso\Shared\Exception\NullGuard;

class IdRequiredException extends MustNotBeNullException
{
  public function __construct()
  {
    parent::__construct('ID required');
  }
}
