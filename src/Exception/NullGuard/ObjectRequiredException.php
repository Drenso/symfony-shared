<?php

namespace Drenso\Shared\Exception\NullGuard;

class ObjectRequiredException extends MustNotBeNullException
{
  public function __construct()
  {
    parent::__construct('Object required');
  }
}
