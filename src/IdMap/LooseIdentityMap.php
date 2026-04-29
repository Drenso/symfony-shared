<?php

namespace Drenso\Shared\IdMap;

/**
 * @template TKey
 * @template TItem
 *
 * @template-extends AbstractIdentityMap<TKey, TItem>
 */
class LooseIdentityMap extends AbstractIdentityMap
{
  /** @param array<TKey, TItem> $objects */
  public function __construct(array $objects = [])
  {
    // Same implementation as fromMappedArray
    $this->elements = $objects;
  }
}
