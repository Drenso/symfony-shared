<?php

namespace Drenso\Shared\IdMap;

use ArrayAccess;
use Countable;
use InvalidArgumentException;
use IteratorAggregate;
use Stringable;
use Traversable;

/**
 * @template TKey
 * @template TItem
 *
 * @template-implements ArrayAccess<TKey, TItem>
 * @template-implements IteratorAggregate<TKey, TItem>
 */
abstract class AbstractIdMap implements ArrayAccess, Countable, IteratorAggregate, Stringable
{
  /** @var array<TKey, TItem> */
  protected array $elements = [];

  /** @param TItem[] $objects */
  abstract public function __construct(array $objects = []);

  /** @param array<TKey, TItem> $data */
  public static function fromMappedArray(array $data = []): static
  {
    $object           = new static();
    $object->elements = $data;

    return $object;
  }

  /** @return array<TKey, TItem> */
  public function toArray(): array
  {
    return $this->elements;
  }

  public function count(): int
  {
    return count($this->elements);
  }

  /** @return TKey[] */
  public function getKeys(): array
  {
    return array_keys($this->elements);
  }

  /** @return TItem[] */
  public function getValues(): array
  {
    return array_values($this->elements);
  }

  /** @return Traversable<TKey, TItem> */
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
