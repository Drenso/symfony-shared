<?php

namespace Drenso\Shared\Helper;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;

class DateTimeHelper
{
  private static ?DateTimeZone $utc = null;

  private static ?DateTimeZone $local = null;

  public static function getUtcTimeZone(): DateTimeZone
  {
    return self::$utc ?: self::$utc = new DateTimeZone('UTC');
  }

  public static function getLocalTimeZone(): DateTimeZone
  {
    return self::$local ?: self::$local = new DateTimeZone(date_default_timezone_get());
  }

  public static function convertTo(
    DateTimeInterface $toConvert,
    ?DateTimeZone $timezone = null): DateTime
  {
    $converted = DateTime::createFromInterface($toConvert);

    return $timezone === null ? $converted : $converted->setTimezone($timezone);
  }

  public static function convertToImmutable(
    DateTimeInterface $toConvert,
    ?DateTimeZone $timezone = null): DateTimeImmutable
  {
    $converted = DateTimeImmutable::createFromInterface($toConvert);

    return $timezone === null ? $converted : $converted->setTimezone($timezone);
  }

  public static function convertToLocal(DateTimeInterface $toConvert): DateTime
  {
    return self::convertTo($toConvert, self::getLocalTimeZone());
  }

  public static function convertToLocalImmutable(DateTimeInterface $toConvert): DateTimeImmutable
  {
    return self::convertToImmutable($toConvert, self::getLocalTimeZone());
  }

  public static function convertToUtc(DateTimeInterface $toConvert): DateTime
  {
    return self::convertTo($toConvert, self::getUtcTimeZone());
  }

  public static function convertToUtcImmutable(DateTimeInterface $toConvert): DateTimeImmutable
  {
    return self::convertToImmutable($toConvert, self::getUtcTimeZone());
  }
}
