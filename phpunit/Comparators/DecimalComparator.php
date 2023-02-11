<?php

namespace Drenso\Shared\Tests\Comparators;

use Decimal\Decimal;
use SebastianBergmann\Comparator\Comparator;
use SebastianBergmann\Comparator\ComparisonFailure;

class DecimalComparator extends Comparator
{

  /**
   * @inheritDoc
   */
  public function accepts(mixed $expected, mixed $actual): bool
  {
    return $expected instanceof Decimal && $actual instanceof Decimal;
  }

  /**
   * @inheritDoc
   */
  public function assertEquals(mixed $expected, mixed $actual, float $delta = 0.0, bool $canonicalize = false, bool $ignoreCase = false): void
  {
    if (!$expected->equals($actual)) {
      throw new ComparisonFailure(
          $expected, $actual,
          sprintf('Value: %s, precision: %d', $expected->toString(), $expected->precision()),
          sprintf('Value: %s, precision: %d', $actual->toString(), $actual->precision()),
      );
    }
  }
}
