<?php

namespace Drenso\Shared\Helper;

use InvalidArgumentException;

class ArrayHelper
{

  /**
   * Verify that all array elements are of the supplied type
   *
   * @param array  $variables
   * @param string $type
   */
  public static function assertType(array $variables, string $type)
  {
    switch ($type) {
      case 'int':
      case 'integer':
        self::assertInt($variables);
        break;
      case 'string':
        self::assertString($variables);
        break;
      case 'float':
        self::assertFloat($variables);
        break;
      case 'bool':
      case 'boolean':
        self::assertBool($variables);
        break;
      case 'array':
        self::assertArray($variables);
        break;
      default:
        self::assertClass($variables, $type);
    }
  }

  /**
   * Verify that all array elements are integers
   *
   * @param array $variables
   */
  public static function assertInt(array $variables)
  {
    foreach ($variables as $variable) {
      if (!is_int($variable)) {
        throw new InvalidArgumentException(
            sprintf('Expected variable to be of type int, but found variable of type "%s"', gettype($variable)));
      }
    }
  }

  /**
   * Verify that all array elements are strings
   *
   * @param array $variables
   */
  public static function assertString(array $variables)
  {
    foreach ($variables as $variable) {
      if (!is_string($variable)) {
        throw new InvalidArgumentException(
            sprintf('Expected variable to be of type string, but found variable of type "%s"', gettype($variable)));
      }
    }
  }

  /**
   * Verify that all array elements are floats
   *
   * @param array $variables
   */
  public static function assertFloat(array $variables)
  {
    foreach ($variables as $variable) {
      if (!is_float($variable)) {
        throw new InvalidArgumentException(
            sprintf('Expected variable to be of type float, but found variable of type "%s"', gettype($variable)));
      }
    }
  }

  /**
   * Verify that all array elements are booleans
   *
   * @param array $variables
   */
  public static function assertBool(array $variables)
  {
    foreach ($variables as $variable) {
      if (!is_bool($variable)) {
        throw new InvalidArgumentException(
            sprintf('Expected variable to be of type bool, but found variable of type "%s"', gettype($variable)));
      }
    }
  }

  /**
   * Verify that all array elements are arrays
   *
   * @param array $variables
   */
  public static function assertArray(array $variables)
  {
    foreach ($variables as $variable) {
      if (!is_array($variable)) {
        throw new InvalidArgumentException(
            sprintf('Expected variable to be of type string, but found variable of type "%s"', gettype($variable)));
      }
    }
  }

  /**
   * Verify that all array elements are objects of the supplied class
   *
   * @param array  $objects
   * @param string $class
   */
  public static function assertClass(array $objects, string $class)
  {
    foreach ($objects as $object) {
      if (!($isObject = is_object($object)) || !$object instanceof $class) {
        throw new InvalidArgumentException(
            sprintf('Expected object to be of type "%s", but found %s of type "%s"', $class, $isObject ? 'object' : 'variable', $isObject ? get_class($object) : gettype($object)));
      }
    }
  }

}
