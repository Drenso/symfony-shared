<?php

namespace Drenso\Shared;

use BackedEnum;

class EnumHelper
{
  /**
   * @template T of BackedEnum
   *
   * @param T[] $enumCases
   *
   * @return array<value-of<T>>
   */
  public static function listToValues(array $enumCases): array
  {
    return array_map(
      static fn (BackedEnum $enum): int|string => $enum->value,
      $enumCases,
    );
  }

  /**
   * @template T of BackedEnum
   *
   * @param T[] $enumCases
   *
   * @return string[]
   */
  public static function listToNames(array $enumCases): array
  {
    return array_map(
      static fn (BackedEnum $enum): string => $enum->name,
      $enumCases,
    );
  }
}
