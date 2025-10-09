<?php

namespace Drenso\Shared\Database\Types;

use DateTime;
use DateTimeImmutable;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\DateTimeImmutableType;
use Doctrine\DBAL\Types\Exception\InvalidFormat;
use Doctrine\DBAL\Types\Exception\InvalidType;
use Drenso\Shared\Helper\DateTimeHelper;
use Override;

/**
 * Store all datetime immutable types as UTC in the database
 * Source: https://www.doctrine-project.org/projects/doctrine-orm/en/2.7/cookbook/working-with-datetime.html.
 */
class UTCDateTimeImmutableType extends DateTimeImmutableType
{
  /** @throws InvalidType */
  #[Override]
  public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
  {
    if ($this->shouldConvertToImmutable() && $value instanceof DateTime) {
      $value = DateTimeImmutable::createFromMutable($value);
    }

    if ($value instanceof DateTimeImmutable) {
      $value = $value->setTimezone(DateTimeHelper::getUtcTimeZone());
    }

    return parent::convertToDatabaseValue($value, $platform);
  }

  /**
   * Whether to convert the application value to an immutable.
   * This method should be overridden when needed.
   */
  protected function shouldConvertToImmutable(): bool
  {
    return false;
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
