<?php

namespace Drenso\Shared\IdMap;

use ArrayAccess;
use Countable;
use Drenso\Shared\Interfaces\IdInterface;
use InvalidArgumentException;
use IteratorAggregate;
use Stringable;
use Traversable;

/**
 * @template T
 *
 * @template-implements ArrayAccess<int, T>
 * @template-implements IteratorAggregate<int, T>
 */
class IdMap implements ArrayAccess, Countable, IteratorAggregate, Stringable
{
  /** @var array<int, T> */
  private array $elements;

  /**
   * @template TConstructor of T&IdInterface
   *
   * @param TConstructor[] $objects
   */
  public function __construct(array $objects = [])
  {
    $this->elements = [];
    foreach ($objects as $object) {
      /* @phpstan-ignore instanceof.alwaysTrue */
      if (!$object instanceof IdInterface) {
        throw new InvalidArgumentException(sprintf('All array items must implement %s', IdInterface::class));
      }

      if (!$id = $object->getId()) {
        throw new InvalidArgumentException('Cannot map object with a non-empty id');
      }

      /* @phan-suppress-next-line PhanTypeMismatchProperty */
      $this->elements[$id] = $object;
    }
  }

  /**
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
  public function getIterator(): Traversable
  {
    yield from $this->elements;
  }

  public function offsetExists(mixed $offset): bool
  {
    return isset($this->elements[$offset]);
  }

  public function offsetGet(mixed $offset): mixed
  {
    return $this->elements[$offset]
        ?? throw new InvalidArgumentException(sprintf('Requested id %d not available', $offset));
  }

  public function offsetSet(mixed $offset, mixed $value): void
  {
    if ($offset === null) {
      throw new InvalidArgumentException('Cannot dynamically add to IdMap with a null key');
    }

    $this->elements[$offset] = $value;
  }

  public function offsetUnset(mixed $offset): void
  {
    unset($this->elements[$offset]);
  }

  public function __toString(): string
  {
    return self::class . '@' . spl_object_hash($this);
  }
}
