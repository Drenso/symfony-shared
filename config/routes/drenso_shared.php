<?php

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return static function (RoutingConfigurator $routing): void {
  $routing->import('@DrensoSharedBundle/Resources/config/routing/routing-sentry.php');
};
