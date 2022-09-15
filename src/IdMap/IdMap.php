<?php

namespace Drenso\Shared\IdMap;

use ArrayAccess;
use ArrayIterator;
use Countable;
use Drenso\Shared\Interfaces\IdInterface;
use InvalidArgumentException;
use IteratorAggregate;
use Traversable;

/**
 * @template T of IdInterface
 * @template-implements IteratorAggregate<int, T>
 */
class IdMap implements ArrayAccess, Countable, IteratorAggregate
{
  /** @var array<int, T> */
  private $elements;

  /** @param T[] $objects */
  public function __construct(array $objects = [])
  {
    $this->elements = [];
    foreach ($objects as $object) {
      if (!$object instanceof IdInterface) {
        throw new InvalidArgumentException(sprintf('All array items must implement %s', IdInterface::class));
      }

      if (!$id = $object->getId()) {
        throw new InvalidArgumentException('Cannot map object with a non-empty id');
      }

      /** @phan-suppress-next-line PhanTypeMismatchProperty */
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

  public function offsetExists($offset): bool
  {
    return isset($this->elements[$offset]);
  }

  public function offsetGet($offset)
  {
    if (!array_key_exists($offset, $this->elements)) {
      throw new InvalidArgumentException(sprintf('Requested id %d not available', $offset));
    }

    return $this->elements[$offset];
  }

  public function offsetSet($offset, $value): void
  {
    if ($offset === null) {
      throw new InvalidArgumentException('Cannot dynamically add to IdMap with a null key');
    }

    $this->elements[$offset] = $value;
  }

  public function offsetUnset($offset): void
  {
    unset($this->elements[$offset]);
  }

  public function __toString()
  {
    return self::class . '@' . spl_object_hash($this);
  }
}
