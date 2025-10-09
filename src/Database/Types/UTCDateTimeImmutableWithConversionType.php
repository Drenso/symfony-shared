<?php

namespace Drenso\Shared\Database\Types;

use Override;

/**
 * Same as UTCDateTimeImmutableType, but convert application values to immutable equivalents.
 */
class UTCDateTimeImmutableWithConversionType extends UTCDateTimeImmutableType
{
  final public const string DATETIME_IMMUTABLE_WITH_CONVERSION
    = DateTimeImmutableWithConversionType::DATETIME_IMMUTABLE_WITH_CONVERSION;

  #[Override]
  protected function shouldConvertToImmutable(): bool
  {
    return true;
  }
}
