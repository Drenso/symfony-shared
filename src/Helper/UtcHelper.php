<?php

namespace Drenso\Shared\Helper;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use RuntimeException;

class UtcHelper
{

  /**
   * @var DateTimeZone
   */
  private static $utc;

  /**
   * @return DateTimeZone
   */
  public static function getUtc(): DateTimeZone
  {
    return self::$utc ?: self::$utc = new DateTimeZone('UTC');
  }

  public static function convertToLocalTimezone(DateTimeInterface $dateTime): DateTimeInterface
  {
    $timezone = (new DateTime())->getTimezone();

    if (PHP_MAJOR_VERSION >= 8) {
      /** @noinspection PhpElementIsNotAvailableInCurrentPhpVersionInspection */
      /** @phan-suppress-next-line PhanUndeclaredStaticMethod */
      return DateTime::createFromInterface($dateTime)->setTimezone($timezone);
    } else if ($dateTime instanceof DateTime) {
      return (clone $dateTime)->setTimezone($timezone);
    } else if ($dateTime instanceof DateTimeImmutable) {
      return $dateTime->setTimezone($timezone);
    }

    throw new RuntimeException('Local timezone could not be set');
  }
}
