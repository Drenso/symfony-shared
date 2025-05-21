<?php

namespace Drenso\Shared\Database\Types;

use DateTime;
use DateTimeImmutable;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\DateTimeType;
use Doctrine\DBAL\Types\Exception\InvalidFormat;
use Doctrine\DBAL\Types\Exception\InvalidType;
use Drenso\Shared\Helper\DateTimeHelper;

/**
 * Store all datetime types as UTC in the database
 * Source: https://www.doctrine-project.org/projects/doctrine-orm/en/2.7/cookbook/working-with-datetime.html.
 */
class UTCDateTimeType extends DateTimeType
{
  /** @throws InvalidType */
  public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
  {
    if ($value instanceof DateTime) {
      $value->setTimezone(DateTimeHelper::getUtcTimeZone());
    }

    return parent::convertToDatabaseValue($value, $platform);
  }

  /**
   * @throws InvalidType
   * @throws InvalidFormat
   */
  public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?DateTime
  {
    if (null === $value || $value instanceof DateTime) {
      return $value;
    }

    if ($value instanceof DateTimeImmutable) {
      return DateTime::createFromImmutable($value);
    }

    if (!is_string($value)) {
      throw InvalidType::new(
        $value,
        static::class,
        ['string']
      );
    }

    $converted = DateTime::createFromFormat(
      $platform->getDateTimeFormatString(),
      $value,
      DateTimeHelper::getUtcTimeZone()
    );

    if (!$converted) {
      throw InvalidFormat::new(
        $value,
        static::class,
        $platform->getDateTimeFormatString()
      );
    }

    return $converted;
  }
}
