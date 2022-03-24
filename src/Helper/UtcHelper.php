<?php

namespace Drenso\Shared\Helper;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;

class UtcHelper
{
  private static ?DateTimeZone $utc = null;

  private static ?DateTimeZone $local = null;

  public static function getUtc(): DateTimeZone
  {
    return self::$utc ?: self::$utc = new DateTimeZone('UTC');
  }

  public static function convertToLocalTimezone(
      DateTimeInterface $dateTime,
      bool $asImmutable = false): DateTimeInterface
  {
    $timezone = self::$local ?: self::$local = new DateTimeZone(date_default_timezone_get());

    $localTime = DateTime::createFromInterface($dateTime)->setTimezone($timezone);
    if ($asImmutable) {
      return DateTimeImmutable::createFromMutable($localTime);
    }

    return $localTime;
  }
}
