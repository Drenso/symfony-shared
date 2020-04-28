<?php

namespace Drenso\Shared\Database\Types;

/**
 * Same as UTCDateTimeImmutableType, but convert application values to immutable equivalents
 */
class UTCDateTimeImmutableWithConversionType extends UTCDateTimeImmutableType
{
  public const DATETIME_IMMUTABLE_WITH_CONVERSION = 'datetime_immutable_with_conversion';

  protected function shouldConvertToImmutable(): bool
  {
    return true;
  }

  public function getName()
  {
    return self::DATETIME_IMMUTABLE_WITH_CONVERSION;
  }
}
