<?php

namespace Drenso\Shared\Database\Types;

use DateTime;
use DateTimeImmutable;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\DateTimeType;
use Drenso\Shared\Helper\DateTimeHelper;

/**
 * Store all datetime types as UTC in the database
 * Source: https://www.doctrine-project.org/projects/doctrine-orm/en/2.7/cookbook/working-with-datetime.html.
 */
class UTCDateTimeType extends DateTimeType
{
  /**
   * @throws ConversionException
   *
   * @return mixed|string|null
   */
  public function convertToDatabaseValue($value, AbstractPlatform $platform)
  {
    if ($value instanceof DateTime) {
      $value->setTimezone(DateTimeHelper::getUtcTimeZone());
    }

    return parent::convertToDatabaseValue($value, $platform);
  }

  /**
   * @throws ConversionException
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
      $platform->getDateTimeFormatString(),
      $value,
      DateTimeHelper::getUtcTimeZone()
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
