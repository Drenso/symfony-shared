<?php

namespace Drenso\Shared\Helper;

use DateTime;
use DateTimeImmutable;

class DateTimeProvider
{
  public function __construct(private readonly string $now = 'now')
  {
  }

  /**
   * The current time, based on the configured server timezone
   * Immutable.
   *
   * @noinspection PhpUnhandledExceptionInspection
   */
  public function now(): DateTimeImmutable
  {
    return new DateTimeImmutable($this->now);
  }

  /**
   * The current time, based on the configured timezone
   * Mutable.
   *
   * @noinspection PhpUnhandledExceptionInspection
   */
  public function nowMutable(): DateTime
  {
    return new DateTime($this->now);
  }

  /**
   * The current time, in UTC
   * Immutable.
   *
   * @noinspection PhpUnhandledExceptionInspection
   */
  public function utcNow(): DateTimeImmutable
  {
    return new DateTimeImmutable($this->now, DateTimeHelper::getUtcTimeZone());
  }

  /**
   * The current time, in UTC
   * Mutable.
   *
   * @noinspection PhpUnhandledExceptionInspection
   */
  public function utcNowMutable(): DateTime
  {
    return new DateTime($this->now, DateTimeHelper::getUtcTimeZone());
  }
}
