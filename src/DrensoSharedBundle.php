<?php

namespace Drenso\Shared;

use Drenso\Shared\Database\Types\DateTimeImmutableWithConversionType;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class DrensoSharedBundle extends Bundle
{
  public function boot()
  {
    if (class_exists('\Gedmo\SoftDeleteable\Mapping\Validator')){
      // Make sure Gedmo allows our specific type
      \Gedmo\SoftDeleteable\Mapping\Validator::$validTypes[]
          = DateTimeImmutableWithConversionType::DATETIME_IMMUTABLE_WITH_CONVERSION;;
    }
  }
}
