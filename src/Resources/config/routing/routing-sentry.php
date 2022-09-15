<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return static function (RoutingConfigurator $routing): void {
  $routing->add('_drenso_sentry_tunnel', '/_stun')
      ->controller('Drenso\Shared\Sentry\SentryTunnelController::tunnel')
      ->methods([Request::METHOD_POST]);
};
