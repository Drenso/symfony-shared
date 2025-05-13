<?php

namespace Drenso\Shared\Database\Types;

use DateTime;
use DateTimeImmutable;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\DateTimeImmutableType;
use Doctrine\DBAL\Types\Exception\InvalidFormat;
use Doctrine\DBAL\Types\Exception\InvalidType;

/**
 * Same as DateTimeImmutableType, but convert application values to immutable equivalents.
 */
class DateTimeImmutableWithConversionType extends DateTimeImmutableType
{
  final public const DATETIME_IMMUTABLE_WITH_CONVERSION = 'datetime_immutable_with_conversion';

  /** @throws InvalidType */
  public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
  {
    if ($value instanceof DateTime) {
      $value = DateTimeImmutable::createFromMutable($value);
    }

    return parent::convertToDatabaseValue($value, $platform);
  }

  /** @throws InvalidFormat */
  public function convertToPHPValue($value, AbstractPlatform $platform): ?DateTimeImmutable
  {
    // Required to support gedmo/doctrine-extension 3.5.0 and higher
    if ($value instanceof DateTime) {
      $value = DateTimeImmutable::createFromMutable($value);
    }

    return parent::convertToPHPValue($value, $platform);
  }

  public function getName(): string
  {
    return self::DATETIME_IMMUTABLE_WITH_CONVERSION;
  }
}
