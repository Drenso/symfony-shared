<?php

namespace Drenso\Shared\Database\Types;

use Decimal\Decimal;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class PhpDecimalType extends StringType
{
  final public const NAME = 'php_decimal';

  /**
   * @param Decimal|null $value
   *
   * @return mixed|string|null
   */
  public function convertToDatabaseValue($value, AbstractPlatform $platform)
  {
    return $value?->toString();
  }

  /**
   * @param mixed $value
   *
   * @return mixed|Decimal|null
   */
  public function convertToPHPValue($value, AbstractPlatform $platform): ?Decimal
  {
    if ($value === null || $value === '') {
      return null;
    }

    return new Decimal($value);
  }

  public function getName(): string
  {
    return self::NAME;
  }

  public function requiresSQLCommentHint(AbstractPlatform $platform): bool
  {
    return true;
  }
}
