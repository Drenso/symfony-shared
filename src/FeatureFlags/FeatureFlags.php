<?php

namespace Drenso\Shared\FeatureFlags;

use Symfony\Component\DependencyInjection\Exception\EnvNotFoundException;
use Symfony\Contracts\Cache\CacheInterface;

class FeatureFlags implements FeatureFlagsInterface
{
  private const CACHE_KEY_MTIME  = 'drenso.feature_flags.mtime';
  private const CACHE_KEY_CONFIG = 'drenso.feature_flags.configuration';

  private ?array $resolvedConfiguration = null;

  public function __construct(
    private readonly string $configuration,
    private readonly string $configurationOverride,
    private readonly bool $jsonCommentParserEnabled,
    private readonly ?CacheInterface $appCache = null)
  {
  }

  public function getFlagValue(string $flag): bool
  {
    // Lazy resolving, only resolve when requested
    $this->resolve();

    if (!array_key_exists($flag, $this->resolvedConfiguration)) {
      throw new FeatureFlagNotConfiguredException($flag);
    }

    if (!is_bool($this->resolvedConfiguration[$flag])) {
      throw new FeatureFlagInvalidTypeException($flag, $this->resolvedConfiguration[$flag]);
    }

    return $this->resolvedConfiguration[$flag];
  }

  private function resolve(): void
  {
    if (null !== $this->resolvedConfiguration) {
      // Configuration already resolved, direct return
      return;
    }

    if (!file_exists($this->configuration) || !is_readable($this->configuration)) {
      throw new EnvNotFoundException(sprintf('Could not find features file %s', $this->configuration));
    }

    $overrideAvailable = true;
    if (!$this->configurationOverride
        || !file_exists($this->configurationOverride)
        || !is_readable($this->configurationOverride)) {
      // No need to throw, as it is an override. Do set an empty default value here.
      $overrideAvailable = false;
    }

    if (!$this->appCache) {
      $this->resolvedConfiguration = $this->parseConfiguration($overrideAvailable);

      return;
    }

    // Validate modify times
    $currentMTime = max(
      filemtime($this->configuration),
      $overrideAvailable ? filemtime($this->configurationOverride) : 0,
    );

    $cachedMTime = $this->appCache->get(self::CACHE_KEY_MTIME, static fn (): int => 0);
    if ($cachedMTime < $currentMTime) {
      // Remove cached values as one of the files has been modified
      $this->appCache->delete(self::CACHE_KEY_MTIME);
      $this->appCache->delete(self::CACHE_KEY_CONFIG);
    }

    // Populate the cache
    $this->resolvedConfiguration = $this->appCache
      ->get(self::CACHE_KEY_CONFIG, fn (): array => $this->parseConfiguration($overrideAvailable));
    $this->appCache->get(self::CACHE_KEY_MTIME, static fn (): int => $currentMTime);
  }

  private function parseConfiguration(bool $overrideAvailable): array
  {
    $configuration         = file_get_contents($this->configuration);
    $configurationOverride = $overrideAvailable ? file_get_contents($this->configurationOverride) : false;

    $configuration         = $configuration ? $this->filterComments($configuration) : '{}';
    $configurationOverride = $configurationOverride ? $this->filterComments($configurationOverride) : '{}';

    return array_merge(
      json_decode($configuration, true, flags: JSON_THROW_ON_ERROR),
      json_decode($configurationOverride, true, flags: JSON_THROW_ON_ERROR),
    );
  }

  private function filterComments(string $data): string
  {
    if (!$this->jsonCommentParserEnabled) {
      return $data;
    }

    // Regex from https://stackoverflow.com/a/43439966
    return preg_replace('~ (" (?:\\\\. | [^"])*+ ") | // \V*+ | /\* .*? \*/ ~xs', '$1', $data);
  }
}
