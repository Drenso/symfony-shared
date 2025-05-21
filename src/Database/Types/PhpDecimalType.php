<?php

namespace Drenso\Shared\Database\Types;

use Decimal\Decimal;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Exception\InvalidType;
use Doctrine\DBAL\Types\StringType;

class PhpDecimalType extends StringType
{
  final public const NAME = 'php_decimal';

  /** @throws InvalidType */
  public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
  {
    if ($value === null) {
      return null;
    }

    if (!$value instanceof Decimal) {
      throw InvalidType::new($value, static::class, [Decimal::class]);
    }

    return $value->toString();
  }

  /** @throws InvalidType */
  public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?Decimal
  {
    if ($value === null || $value === '') {
      return null;
    }

    if ($value instanceof Decimal) {
      return $value;
    }

    if (!is_string($value)) {
      throw InvalidType::new($value, static::class, ['string']);
    }

    return new Decimal($value);
  }

  public function requiresSQLCommentHint(AbstractPlatform $platform): bool
  {
    return true;
  }
}
