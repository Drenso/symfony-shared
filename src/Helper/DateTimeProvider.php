<?php

namespace Drenso\Shared\Helper;

use DateTime;
use DateTimeImmutable;

/**
 * Class DateTimeProvider
 */
class DateTimeProvider
{
  /**
   * The current time, based on the configured server timezone
   * Immutable
   */
  public function now(): DateTimeImmutable
  {
    return new DateTimeImmutable();
  }

  /**
   * The current time, based on the configured timezone
   * Mutable.
   */
  public function nowMutable(): DateTime
  {
    return new DateTime();
  }

  /**
   * The current time, in UTC
   * Immutable
   *
   * @noinspection PhpUnhandledExceptionInspection
   */
  public function utcNow(): DateTimeImmutable
  {
    return new DateTimeImmutable('now', UtcHelper::getUtc());
  }

  /**
   * The current time, in UTC
   * Mutable.
   *
   * @noinspection PhpUnhandledExceptionInspection
   */
  public function utcNowMutable(): DateTime
  {
    return new DateTime('now', UtcHelper::getUtc());
  }
}
