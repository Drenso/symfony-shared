<?php

namespace Drenso\Shared\Database\Types;

use DateTime;
use DateTimeImmutable;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\DateType;
use Drenso\Shared\Helper\UtcHelper;
use Exception;

class UTCDateType extends DateType
{
  /**
   * @throws Exception
   *
   * @return mixed|string|null
   */
  public function convertToDatabaseValue($value, AbstractPlatform $platform)
  {
    if ($value instanceof DateTime && $value->getTimezone() !== UtcHelper::getUtc()) {
      $value = new DateTime($value->format('Y-m-d'), UtcHelper::getUtc());
    }

    return parent::convertToDatabaseValue($value, $platform);
  }

  /**
   * @throws Exception
   *
   * @return DateTime|null
   */
  public function convertToPHPValue($value, AbstractPlatform $platform)
  {
    if (null === $value || $value instanceof DateTime) {
      return $value;
    }

    if ($value instanceof DateTimeImmutable) {
      return DateTime::createFromImmutable($value);
    }

    if (!is_string($value)) {
      throw ConversionException::conversionFailedFormat(
        $value,
        $this->getName(),
        $platform->getDateTimeFormatString()
      );
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
