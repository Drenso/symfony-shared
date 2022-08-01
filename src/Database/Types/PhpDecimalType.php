<?php

namespace Drenso\Shared\Database\Types;

use Decimal\Decimal;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use InvalidArgumentException;

class PhpDecimalType extends StringType
{
  final public const NAME = 'php_decimal';

  public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
  {
    if ($value === null) {
      return null;
    }

    if (!$value instanceof Decimal) {
      throw new InvalidArgumentException(sprintf('Expected %s, got %s', Decimal::class, get_debug_type($value)));
    }

    return $value->toString();
  }

  public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?Decimal
  {
    if ($value === null || $value === '') {
      return null;
    }

    if ($value instanceof Decimal) {
      return $value;
    }

    if (!is_string($value)) {
      throw new InvalidArgumentException(sprintf('Expected string, got %s', get_debug_type($value)));
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
