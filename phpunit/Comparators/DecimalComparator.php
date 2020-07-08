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
  public function accepts($expected, $actual)
  {
    return $expected instanceof Decimal && $actual instanceof Decimal;
  }

  /**
   * @inheritDoc
   */
  public function assertEquals($expected, $actual, $delta = 0.0, $canonicalize = false, $ignoreCase = false)
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
