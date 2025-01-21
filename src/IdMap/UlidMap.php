<?php

namespace Drenso\Shared\IdMap;

use Drenso\Shared\Interfaces\UlidInterface;
use InvalidArgumentException;

/**
 * @template T
 *
 * @template-extends AbstractIdMap<string, T>
 */
class UlidMap extends AbstractIdMap
{
  /**
   * @template TConstructor of T&UlidInterface
   *
   * @param TConstructor[] $objects
   */
  public function __construct(array $objects = [])
  {
    foreach ($objects as $object) {
      /* @phpstan-ignore instanceof.alwaysTrue */
      if (!$object instanceof UlidInterface) {
        throw new InvalidArgumentException(sprintf('All array items must implement %s', UlidInterface::class));
      }

      $this->elements[$object->getUlidAsString()] = $object;
    }
  }
}
