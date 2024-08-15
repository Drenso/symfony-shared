<?php

namespace Drenso\Shared\Exception\NullGuard;

class ReferenceRequiredException extends MustNotBeNullException
{
  public function __construct()
  {
    parent::__construct('Reference required');
  }
}
