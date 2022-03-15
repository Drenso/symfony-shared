<?php

namespace Drenso\Shared\Helper;

use Drenso\Shared\Interfaces\IdInterface;
use InvalidArgumentException;

class ArrayHelper
{
  /** Verify that all array elements are of the supplied type. */
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

  /** Verify that all array elements are integers. */
  public static function assertInt(array $variables)
  {
    foreach ($variables as $variable) {
      if (!is_int($variable)) {
        throw new InvalidArgumentException(
            sprintf('Expected variable to be of type int, but found variable of type "%s"', gettype($variable)));
      }
    }
  }

  /** Verify that all array elements are strings. */
  public static function assertString(array $variables)
  {
    foreach ($variables as $variable) {
      if (!is_string($variable)) {
        throw new InvalidArgumentException(
            sprintf('Expected variable to be of type string, but found variable of type "%s"', gettype($variable)));
      }
    }
  }

  /** Verify that all array elements are floats. */
  public static function assertFloat(array $variables)
  {
    foreach ($variables as $variable) {
      if (!is_float($variable)) {
        throw new InvalidArgumentException(
            sprintf('Expected variable to be of type float, but found variable of type "%s"', gettype($variable)));
      }
    }
  }

  /** Verify that all array elements are booleans. */
  public static function assertBool(array $variables)
  {
    foreach ($variables as $variable) {
      if (!is_bool($variable)) {
        throw new InvalidArgumentException(
            sprintf('Expected variable to be of type bool, but found variable of type "%s"', gettype($variable)));
      }
    }
  }

  /** Verify that all array elements are arrays. */
  public static function assertArray(array $variables)
  {
    foreach ($variables as $variable) {
      if (!is_array($variable)) {
        throw new InvalidArgumentException(
            sprintf('Expected variable to be of type string, but found variable of type "%s"', gettype($variable)));
      }
    }
  }

  /** Verify that all array elements are objects of the supplied class. */
  public static function assertClass(array $objects, string $class)
  {
    foreach ($objects as $object) {
      if (!($isObject = is_object($object)) || !$object instanceof $class) {
        throw new InvalidArgumentException(
            sprintf('Expected object to be of type "%s", but found %s of type "%s"', $class, $isObject ? 'object' : 'variable', $isObject ? $object::class : gettype($object)));
      }
    }
  }

  /**
   * Create an indexed (by id) array from the array input.
   * Note that the IdMap object is recommended when the data is being serialized to JSON.
   *
   * @template T of IdInterface
   *
   * @param T[] $objects
   *
   * @return array<int, T>
   */
  public static function mapById(array $objects): array
  {
    $result = [];
    foreach ($objects as $object) {
      $result[$object->getId()] = $object;
    }

    return $result;
  }

  /**
   * Map an array of object to an array of ids.
   *
   * @param IdInterface[] $objects
   *
   * @return int[]
   */
  public static function mapToId(array $objects): array
  {
    return array_values(array_map(
        fn ($object) => $object->getId(),
        $objects
    ));
  }

  /**
   * Filter null values from array.
   *
   * @template T
   *
   * @param array<T|null> $data
   *
   * @return array<T>
   */
  public static function filterNullValuesFromArray(array $data): array
  {
    return array_values(array_filter($data, fn ($item) => $item !== null));
  }
}
