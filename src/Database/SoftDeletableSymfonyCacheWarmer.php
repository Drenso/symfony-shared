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

  /** @return string[] */
  public function warmUp(string $cacheDir, ?string $buildDir = null): array
  {
    self::registerGedmoType();
    SoftDeletableSymfonySubscriber::registerConversionTypeStatic($this->useUtc);

    return [];
  }
}
