<?php

namespace Drenso\Shared\IdMap;

use Drenso\Shared\Interfaces\IdInterface;
use InvalidArgumentException;

/**
 * @template T
 *
 * @template-extends AbstractIdMap<int, T>
 */
class IdMap extends AbstractIdMap
{
  /**
   * @template TConstructor of T&IdInterface
   *
   * @param TConstructor[] $objects
   */
  public function __construct(array $objects = [])
  {
    foreach ($objects as $object) {
      /* @phpstan-ignore instanceof.alwaysTrue */
      if (!$object instanceof IdInterface) {
        throw new InvalidArgumentException(sprintf('All array items must implement %s', IdInterface::class));
      }

      $id = $object->getId() ?? throw new InvalidArgumentException('Cannot map object with a non-empty id');

      $this->elements[$id] = $object;
    }
  }
}
