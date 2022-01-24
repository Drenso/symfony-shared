<?php

namespace Drenso\Shared\FeatureFlags;

use Symfony\Component\DependencyInjection\Exception\EnvNotFoundException;

class FeatureFlags
{
  private ?array $resolvedConfiguration = null;

  public function __construct(private string $configuration, private string $configurationOverride)
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

  private function resolve()
  {
    if (null !== $this->resolvedConfiguration) {
      return;
    }

    if (!file_exists($this->configuration) || !is_readable($this->configuration)) {
      throw new EnvNotFoundException(sprintf('Could not find features file %s', $this->configuration));
    }

    if (!$this->configurationOverride
        || !file_exists($this->configurationOverride)
        || !is_readable($this->configurationOverride)) {
      // No need to throw, as it is an override. Do set an empty default value here.
      $configurationOverride = '{}';
    } else {
      $configurationOverride = file_get_contents($this->configurationOverride) ?? '{}';
    }

    $configuration               = file_get_contents($this->configuration) ?? '{}';
    $this->resolvedConfiguration = array_merge(
        json_decode($configuration, true, flags: JSON_THROW_ON_ERROR),
        json_decode($configurationOverride, true, flags: JSON_THROW_ON_ERROR),
    );
  }
}
