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
   * @param mixed $value
   *
   * @return mixed|string|null
   *
   * @throws ConversionException
   */
  public function convertToDatabaseValue($value, AbstractPlatform $platform)
  {
    if ($value instanceof DateTime) {
      $value = DateTimeImmutable::createFromMutable($value);
    }

    return parent::convertToDatabaseValue($value, $platform);
  }

  public function getName(): string
  {
    return self::DATETIME_IMMUTABLE_WITH_CONVERSION;
  }
}
