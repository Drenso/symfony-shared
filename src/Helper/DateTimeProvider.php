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
   * @return DateTimeImmutable
   * @noinspection PhpDocMissingThrowsInspection
   */
  public function now(): DateTimeImmutable
  {
    return new DateTimeImmutable();
  }

  /**
   * @return DateTime
   * @noinspection PhpDocMissingThrowsInspection
   */
  public function nowMutable(): DateTime
  {
    return new DateTime();
  }
}
