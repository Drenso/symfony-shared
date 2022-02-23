<?php

namespace Drenso\Shared\Database;

use Drenso\Shared\Database\Types\DateTimeImmutableWithConversionType;
use Gedmo\SoftDeleteable\Mapping\Validator;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;

class SoftDeletableSymfonyCacheWarmer implements CacheWarmerInterface
{
  public function __construct(private readonly bool $useUtc)
  {
  }

  public static function registerGedmoType(): void
  {
    Validator::$validTypes[] = DateTimeImmutableWithConversionType::DATETIME_IMMUTABLE_WITH_CONVERSION;
  }

  public function isOptional(): bool
  {
    return false;
  }

  /**
   * @return string[]
   * @suppress PhanParamSignatureRealMismatchHasNoParamType
   */
  public function warmUp(string $cacheDir): array
  {
    self::registerGedmoType();
    SoftDeletableSymfonySubscriber::registerConversionTypeStatic($this->useUtc);

    return [];
  }
}
