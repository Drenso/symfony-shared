<?php

namespace Drenso\Shared\Helper;

use InvalidArgumentException;

class ArrayHelper
{

  /**
   * Verify that all array elements are of the supplied type
   *
   * @param array  $objects
   * @param string $type
   */
  public static function assertType(array $objects, string $type)
  {
    foreach ($objects as $object) {
      if (!$object instanceof $type) {
        throw new InvalidArgumentException(
            sprintf('Expected object to be of type "%s", but found object of type "%s"', $type, get_class($object)));
      }
    }
  }
}
