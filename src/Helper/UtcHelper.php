<?php

namespace Drenso\Shared\Helper;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use RuntimeException;

class UtcHelper
{
  private static \DateTimeZone $utc;

  private static \DateTimeZone $local;

  public static function getUtc(): DateTimeZone
  {
    return self::$utc ?: self::$utc = new DateTimeZone('UTC');
  }

  public static function convertToLocalTimezone(DateTimeInterface $dateTime): DateTimeInterface
  {
    $timezone = self::$local ?: self::$local = new DateTimeZone(date_default_timezone_get());

    if (PHP_MAJOR_VERSION >= 8) {
      return DateTime::createFromInterface($dateTime)->setTimezone($timezone);
    } elseif ($dateTime instanceof DateTime) {
      return (clone $dateTime)->setTimezone($timezone);
    } elseif ($dateTime instanceof DateTimeImmutable) {
      return $dateTime->setTimezone($timezone);
    }

    throw new RuntimeException('Local timezone could not be set');
  }
}
