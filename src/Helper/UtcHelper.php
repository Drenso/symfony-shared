<?php

namespace Drenso\Shared\Helper;

use DateTimeZone;

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
}
