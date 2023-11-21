<?php

namespace Drenso\Shared\Database\Types;

use DateTime;
use DateTimeImmutable;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\DateImmutableType;
use Drenso\Shared\Helper\UtcHelper;
use Exception;

class UTCDateImmutableType extends DateImmutableType
{
  /**
   * @throws Exception
   *
   * @return mixed|string|null
   */
  public function convertToDatabaseValue($value, AbstractPlatform $platform)
  {
    if ($value instanceof DateTimeImmutable) {
      $value = new DateTimeImmutable($value->format('Y-m-d'), UtcHelper::getUtc());
    }

    return parent::convertToDatabaseValue($value, $platform);
  }

  /**
   * @throws Exception
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
      '!' . $platform->getDateFormatString(),
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
