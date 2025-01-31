<?php

namespace Drenso\Shared\Logging\FilterHandler;

use Monolog\Handler\AbstractHandler;
use Monolog\LogRecord;

class ZenstruckCacheLoggingFilterHandler extends AbstractHandler
{
  public function isHandling(LogRecord $record): bool
  {
    return true;
  }

  public function handle(LogRecord $record): bool
  {
    if ($record->channel !== 'cache') {
      return false;
    }

    if (!str_starts_with($record->message, 'Lock acquired, now computing item')) {
      return false;
    }

    $key = $record->context['key'] ?? null;
    if (is_string($key) && str_starts_with($key, 'zenstruck_messenger_monitor.worker.')) {
      return true;
    }

    return false;
  }
}
