<?php

namespace Drenso\Shared\Database;

use Drenso\Shared\Database\Types\DateTimeImmutableWithConversionType;
use Gedmo\SoftDeleteable\Mapping\Validator;
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

  public static function registerGedmoType(): void
  {
    Validator::$validTypes[] = DateTimeImmutableWithConversionType::DATETIME_IMMUTABLE_WITH_CONVERSION;
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
    self::registerGedmoType();
    SoftDeletableSymfonySubscriber::registerConversionTypeStatic($this->useUtc);

    return [];
  }
}
