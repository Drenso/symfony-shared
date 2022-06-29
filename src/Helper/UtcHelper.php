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

  public static function getLocal(): DateTimeZone
  {
    return self::$local ?: self::$local = new DateTimeZone(date_default_timezone_get());
  }

  /** @deprecated You should switch to the strictly typed alternatives */
  public static function convertToLocalTimezone(
      DateTimeInterface $dateTime,
      bool $asImmutable = false): DateTimeInterface
  {
    return $asImmutable
        ? self::convertToLocalImmutable($dateTime)
        : self::convertToLocal($dateTime);
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
    return self::convertTo($toConvert, self::getLocal());
  }

  public static function convertToLocalImmutable(DateTimeInterface $toConvert): DateTimeImmutable
  {
    return self::convertToImmutable($toConvert, self::getLocal());
  }

  public static function convertToUtc(DateTimeInterface $toConvert): DateTime
  {
    return self::convertTo($toConvert, self::getUtc());
  }

  public static function convertToUtcImmutable(DateTimeInterface $toConvert): DateTimeImmutable
  {
    return self::convertToImmutable($toConvert, self::getUtc());
  }
}
