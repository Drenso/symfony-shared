<?php

namespace Drenso\Shared\Helper;

use DateTimeInterface;
use DateTimeZone;

/**
 * @deprecated Replaced by a new class
 * @see        DateTimeHelper
 */
class UtcHelper
{
  public static function getUtc(): DateTimeZone
  {
    return DateTimeHelper::getUtcTimeZone();
  }

  public static function getLocal(): DateTimeZone
  {
    return DateTimeHelper::getLocalTimeZone();
  }

  public static function convertToLocalTimezone(
    DateTimeInterface $dateTime,
    bool $asImmutable = false): DateTimeInterface
  {
    return $asImmutable
        ? DateTimeHelper::convertToLocalImmutable($dateTime)
        : DateTimeHelper::convertToLocal($dateTime);
  }
}
