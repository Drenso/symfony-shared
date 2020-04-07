<?php

namespace Drenso\Shared\Helper;

use DateTime;

/**
 * Class DateTimeProvider
 */
class DateTimeProvider
{
  /**
   * @return DateTime
   * @noinspection PhpDocMissingThrowsInspection
   */
  public function now(): DateTime
  {
    return new DateTime();
  }
}