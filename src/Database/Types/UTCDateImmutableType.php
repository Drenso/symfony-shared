<?php

namespace Drenso\Shared\Database\Types;

use DateTimeImmutable;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\DateImmutableType;
use Drenso\Shared\Helper\UtcHelper;
use Exception;

class UTCDateImmutableType extends DateImmutableType
{
  /**
   * @param mixed            $value
   * @param AbstractPlatform $platform
   *
   * @return mixed|string|null
   * @throws Exception
   */
  public function convertToDatabaseValue($value, AbstractPlatform $platform)
  {
    if ($value instanceof DateTimeImmutable) {
      $value = new DateTimeImmutable($value->format('Y-m-d'), UtcHelper::getUtc());
    }

    return parent::convertToDatabaseValue($value, $platform); // TODO: Change the autogenerated stub
  }

  /**
   * @param mixed            $value
   * @param AbstractPlatform $platform
   *
   * @return DateTimeImmutable|null
   * @throws Exception
   */
  public function convertToPHPValue($value, AbstractPlatform $platform)
  {
    if (NULL === $value || $value instanceof DateTimeImmutable) {
      return $value;
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