<?php

namespace Drenso\Shared\Helper;

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
}
