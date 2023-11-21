<?php

namespace Drenso\Shared\Database\Types;

use DateTime;
use DateTimeImmutable;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\DateTimeImmutableType;

/**
 * Same as DateTimeImmutableType, but convert application values to immutable equivalents.
 */
class DateTimeImmutableWithConversionType extends DateTimeImmutableType
{
  final public const DATETIME_IMMUTABLE_WITH_CONVERSION = 'datetime_immutable_with_conversion';

  /**
   * @throws ConversionException
   *
   * @return mixed|string|null
   */
  public function convertToDatabaseValue($value, AbstractPlatform $platform)
  {
    if ($value instanceof DateTime) {
      $value = DateTimeImmutable::createFromMutable($value);
    }

    return parent::convertToDatabaseValue($value, $platform);
  }

  /**
   * @throws ConversionException
   *
   * @return DateTimeImmutable|null
   */
  public function convertToPHPValue($value, AbstractPlatform $platform)
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
