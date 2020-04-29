<?php

namespace Drenso\Shared\Database;

use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;

class SoftDeleteableSymfonyCacheWarmer implements CacheWarmerInterface
{
  /**
   * @var bool
   */
  private $useUtc;

  public function __construct(bool $useUtc)
  {
    $this->useUtc = $useUtc;
  }

  public function isOptional()
  {
    return false;
  }

  public function warmUp(string $cacheDir)
  {
    (new SoftDeleteableSymfonySubscriber($this->useUtc))->registerConversionType();

    return [];
  }
}
