<?php

namespace Drenso\Shared\Database\Types;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\DateTimeImmutableType;
use Drenso\Shared\Helper\UtcHelper;

/**
 * Class UTCDateTimeImmutableType
 * Store all datetime immutable types as UTC in the database
 * Source: https://www.doctrine-project.org/projects/doctrine-orm/en/2.7/cookbook/working-with-datetime.html
 */
class UTCDateTimeImmutableType extends DateTimeImmutableType
{
  /**
   * @param mixed            $value
   * @param AbstractPlatform $platform
   *
   * @return mixed|string|null
   * @throws ConversionException
   */
  public function convertToDatabaseValue($value, AbstractPlatform $platform)
  {
    if ($value instanceof DateTimeImmutable) {
      $value = $value->setTimezone(UtcHelper::getUtc());
    }

    return parent::convertToDatabaseValue($value, $platform);
  }

  /**
   * @param mixed            $value
   * @param AbstractPlatform $platform
   *
   * @return DateTimeImmutable|DateTimeInterface|false|mixed|null
   * @throws ConversionException
   */
  public function convertToPHPValue($value, AbstractPlatform $platform)
  {
    if (NULL === $value || $value instanceof DateTimeImmutable) {
      return $value;
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
