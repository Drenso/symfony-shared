<?php

namespace Drenso\Shared\MemoryCache;

use Closure;

trait InMemoryCacheTrait
{
  private array $_memCache = [];

  /**
   * @template T
   *
   * @param Closure():T $builder
   *
   * @return T
   */
  protected function memCachedResult(string $key, Closure $builder): mixed
  {
    return $this->_memCache[$key] ??= $builder();
  }

  protected function memCachedClear(): void
  {
    $this->_memCache = [];
  }
}
