<?php

namespace Drenso\Shared\Database\Types;

use DateTime;
use DateTimeImmutable;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\DateTimeImmutableType;
use Drenso\Shared\Helper\UtcHelper;

/**
 * Store all datetime immutable types as UTC in the database
 * Source: https://www.doctrine-project.org/projects/doctrine-orm/en/2.7/cookbook/working-with-datetime.html.
 */
class UTCDateTimeImmutableType extends DateTimeImmutableType
{
  /**
   * @throws ConversionException
   *
   * @return mixed|string|null
   */
  public function convertToDatabaseValue($value, AbstractPlatform $platform)
  {
    if ($this->shouldConvertToImmutable() && $value instanceof DateTime) {
      $value = DateTimeImmutable::createFromMutable($value);
    }

    if ($value instanceof DateTimeImmutable) {
      $value = $value->setTimezone(UtcHelper::getUtc());
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
   * @throws ConversionException
   *
   * @return DateTimeImmutable|null
   */
  public function convertToPHPValue($value, AbstractPlatform $platform)
  {
    if (null === $value || $value instanceof DateTimeImmutable) {
      return $value;
    }

    if ($value instanceof DateTime) {
      return DateTimeImmutable::createFromMutable($value);
    }

    if (!is_string($value)) {
      throw ConversionException::conversionFailedFormat(
        $value,
        $this->getName(),
        $platform->getDateTimeFormatString()
      );
    }

    $converted = DateTimeImmutable::createFromFormat(
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
