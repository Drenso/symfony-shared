<?php

namespace Drenso\Shared\Database\Types;

use DateTime;
use DateTimeImmutable;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\DateImmutableType;
use Doctrine\DBAL\Types\Exception\InvalidFormat;
use Doctrine\DBAL\Types\Exception\InvalidType;
use Drenso\Shared\Helper\DateTimeHelper;
use Exception;
use Override;

class UTCDateImmutableType extends DateImmutableType
{
  /** @throws Exception */
  #[Override]
  public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
  {
    if ($value instanceof DateTimeImmutable) {
      $value = new DateTimeImmutable($value->format('Y-m-d'), DateTimeHelper::getUtcTimeZone());
    }

    return parent::convertToDatabaseValue($value, $platform);
  }

  /**
   * @throws InvalidType
   * @throws InvalidFormat
   */
  #[Override]
  public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?DateTimeImmutable
  {
    if (null === $value || $value instanceof DateTimeImmutable) {
      return $value;
    }

    if ($value instanceof DateTime) {
      return DateTimeImmutable::createFromMutable($value);
    }

    if (!is_string($value)) {
      throw InvalidType::new(
        $value,
        static::class,
        ['string']
      );
    }

    $converted = DateTimeImmutable::createFromFormat(
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
