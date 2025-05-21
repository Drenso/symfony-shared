<?php

namespace Drenso\Shared\Database\Types;

/**
 * Same as UTCDateTimeImmutableType, but convert application values to immutable equivalents.
 */
class UTCDateTimeImmutableWithConversionType extends UTCDateTimeImmutableType
{
  final public const DATETIME_IMMUTABLE_WITH_CONVERSION
    = DateTimeImmutableWithConversionType::DATETIME_IMMUTABLE_WITH_CONVERSION;

  protected function shouldConvertToImmutable(): bool
  {
    return true;
  }
}
