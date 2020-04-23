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
   */
  public function now(): DateTimeImmutable
  {
    return new DateTimeImmutable();
  }

  /**
   * @return DateTime
   */
  public function nowMutable(): DateTime
  {
    return new DateTime();
  }
}
