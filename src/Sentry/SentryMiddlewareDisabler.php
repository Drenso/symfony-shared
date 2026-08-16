<?php

namespace Drenso\Shared\Sentry;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Middleware;
use Sentry\SentryBundle\Tracing\Doctrine\DBAL\TracingDriverMiddleware;

class SentryMiddlewareDisabler
{
  /** Used to disable Sentry middleware to prevent memory issues. */
  public static function disableSentryMiddleware(Connection $connection): void
  {
    $configuration = $connection->getConfiguration();

    // Remove logging and debug middlewares from the configuration
    $filteredMiddlewares = array_filter(
      $configuration->getMiddlewares(),
      static fn (
        Middleware $middleware,
      ): bool => !$middleware instanceof TracingDriverMiddleware,
    );

    // Update the configured middleware
    $configuration->setMiddlewares($filteredMiddlewares);
  }
}
