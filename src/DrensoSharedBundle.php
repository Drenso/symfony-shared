<?php

namespace Drenso\Shared;

use Drenso\Shared\Database\SoftDeletableSymfonyCacheWarmer;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class DrensoSharedBundle extends Bundle
{
  public function boot()
  {
    if (class_exists('\Gedmo\SoftDeleteable\Mapping\Validator')){
      // Make sure Gedmo allows our specific type
      SoftDeletableSymfonyCacheWarmer::registerGedmoType();
    }
  }
}
