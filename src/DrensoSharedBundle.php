<?php

namespace Drenso\Shared;

use Drenso\Shared\Database\SoftDeletableSymfonyCacheWarmer;
use Gedmo\SoftDeleteable\Mapping\Validator;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class DrensoSharedBundle extends Bundle
{
  public function boot(): void
  {
    if (class_exists(Validator::class)) {
      // Make sure Gedmo allows our specific type
      SoftDeletableSymfonyCacheWarmer::registerGedmoType();
    }
  }
}
