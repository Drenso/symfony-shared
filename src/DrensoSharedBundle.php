<?php

namespace Drenso\Shared;

use Drenso\Shared\Database\DisablePostFlushDetachCompilerPass;
use Drenso\Shared\Database\SoftDeletableSymfonyCacheWarmer;
use Gedmo\SoftDeleteable\Mapping\Validator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
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

  public function build(ContainerBuilder $container): void
  {
    // Needs to be run before RegisterEventListenersAndSubscribersPass is run
    $container->addCompilerPass(new DisablePostFlushDetachCompilerPass(), priority: 10);
  }
}
