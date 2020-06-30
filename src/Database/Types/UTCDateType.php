<?php

namespace Drenso\Shared\Database\Types;

use DateTime;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\DateType;
use Drenso\Shared\Helper\UtcHelper;
use Exception;

class UTCDateType extends DateType
{
  /**
   * @param mixed            $value
   * @param AbstractPlatform $platform
   *
   * @return mixed|string|null
   * @throws Exception
   */
  public function convertToDatabaseValue($value, AbstractPlatform $platform)
  {
    if ($value instanceof DateTime && $value->getTimezone() !== UtcHelper::getUtc()) {
      $value = new DateTime($value->format('Y-m-d'), UtcHelper::getUtc());
    }

    return parent::convertToDatabaseValue($value, $platform);
  }

  /**
   * @param mixed            $value
   * @param AbstractPlatform $platform
   *
   * @return DateTime|null
   * @throws Exception
   */
  public function convertToPHPValue($value, AbstractPlatform $platform)
  {
    if (NULL === $value || $value instanceof DateTime) {
      return $value;
    }

    $converted = DateTime::createFromFormat(
        '!' . $platform->getDateFormatString(),
        $value,
        UtcHelper::getUtc()
    );

    if (!$converted) {
      throw ConversionException::conversionFailedFormat(
          $value,
          $this->getName(),
          $platform->getDateTimeFormatString()
      );
    }

    return $converted;
  }
}
