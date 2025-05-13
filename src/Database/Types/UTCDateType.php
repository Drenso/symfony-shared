<?php

namespace Drenso\Shared\Database\Types;

use DateTime;
use DateTimeImmutable;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\DateType;
use Doctrine\DBAL\Types\Exception\InvalidFormat;
use Doctrine\DBAL\Types\Exception\InvalidType;
use Drenso\Shared\Helper\DateTimeHelper;
use Exception;

class UTCDateType extends DateType
{
  /** @throws Exception */
  public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
  {
    if ($value instanceof DateTime && $value->getTimezone() !== DateTimeHelper::getUtcTimeZone()) {
      $value = new DateTime($value->format('Y-m-d'), DateTimeHelper::getUtcTimeZone());
    }

    return parent::convertToDatabaseValue($value, $platform);
  }

  /**
   * @throws InvalidType
   * @throws InvalidFormat
   */
  public function convertToPHPValue($value, AbstractPlatform $platform): ?DateTime
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
      '!' . $platform->getDateFormatString(),
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
