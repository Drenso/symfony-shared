<?php

namespace Drenso\Shared\Helper;

class StringHelper
{
  /** Converts an empty string to NULL. */
  public static function emptyToNull(?string $value): ?string
  {
    if ($value === null) {
      return null;
    }

    $value = trim($value);

    return $value === '' ? null : $value;
  }
}
