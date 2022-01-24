<?php

namespace Drenso\Shared\Database\Types;

use DateTime;
use DateTimeImmutable;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\DateTimeType;
use Drenso\Shared\Helper\UtcHelper;

/**
 * Class UTCDateTimeType
 * Store all datetime types as UTC in the database
 * Source: https://www.doctrine-project.org/projects/doctrine-orm/en/2.7/cookbook/working-with-datetime.html.
 */
class UTCDateTimeType extends DateTimeType
{
  /**
   * @param mixed $value
   *
   * @return mixed|string|null
   *
   * @throws ConversionException
   */
  public function convertToDatabaseValue($value, AbstractPlatform $platform)
  {
    if ($value instanceof DateTime) {
      $value->setTimezone(UtcHelper::getUtc());
    }

    return parent::convertToDatabaseValue($value, $platform);
  }

  /**
   * @param mixed $value
   *
   * @return DateTime|null
   *
   * @throws ConversionException
   */
  public function convertToPHPValue($value, AbstractPlatform $platform)
  {
    if (null === $value || $value instanceof DateTime) {
      return $value;
    }

    if ($value instanceof DateTimeImmutable) {
      return DateTime::createFromImmutable($value);
    }

    $converted = DateTime::createFromFormat(
        $platform->getDateTimeFormatString(),
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
