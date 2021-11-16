<?php

namespace Drenso\Shared\IdMap;

use ArrayIterator;
use Countable;
use InvalidArgumentException;
use IteratorAggregate;
use Traversable;

/**
 * @template T of IdInterface
 * @template-implements IteratorAggregate<int, T>
 */
class IdMap implements Countable, IteratorAggregate
{
  /** @var array<int, T> */
  private $elements;

  /** @param T[] $objects */
  public function __construct(array $objects = [])
  {
    $this->elements = [];
    foreach ($objects as $object) {
      if (!$id = $object->getId()) {
        throw new InvalidArgumentException('Cannot map object with a non-empty id');
      }

      $this->elements[$id] = $object;
    }
  }

  /**
   * @template T
   *
   * @param array<int, T> $data
   *
   * @return IdMap<T>
   */
  public static function fromMappedArray(array $data = []): self
  {
    $object           = new self();
    $object->elements = $data;

    return $object;
  }

  /** @return array<int, T> */
  public function toArray(): array
  {
    return $this->elements;
  }

  public function count(): int
  {
    return count($this->elements);
  }

  /** @return int[] */
  public function getKeys(): array
  {
    return array_keys($this->elements);
  }

  /** @return T[] */
  public function getValues(): array
  {
    return array_values($this->elements);
  }

  /** @return Traversable<int, T> */
  public function getIterator()
  {
    return new ArrayIterator($this->elements);
  }

  public function __toString()
  {
    return self::class . '@' . spl_object_hash($this);
  }
}
