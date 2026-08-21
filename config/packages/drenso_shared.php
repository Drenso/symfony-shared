<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Config\DrensoSharedConfig;

return static function (DrensoSharedConfig $drensoShared, ContainerConfigurator $container): void {
  $drensoShared->publicServices('test' === $container->env());
};
