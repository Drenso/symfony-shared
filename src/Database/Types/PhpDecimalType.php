<?php

namespace Drenso\Shared\Database\Types;

use Decimal\Decimal;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class PhpDecimalType extends StringType
{
  final public const NAME = 'php_decimal';

  /** @param Decimal|null $value */
  public function convertToDatabaseValue($value, AbstractPlatform $platform)
  {
    return $value?->toString();
  }

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
