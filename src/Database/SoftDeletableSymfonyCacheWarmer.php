<?php

namespace Drenso\Shared\Database;

use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;

class SoftDeletableSymfonyCacheWarmer implements CacheWarmerInterface
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

  /**
   * @param string $cacheDir
   *
   * @return array|string[]
   *
   * @suppress PhanParamSignatureRealMismatchHasNoParamType
   */
  public function warmUp($cacheDir)
  {
    (new SoftDeletableSymfonySubscriber($this->useUtc))->registerConversionType();

    return [];
  }
}
