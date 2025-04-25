<?php

namespace Drenso\Shared\Env\Processor;

use Closure;
use Symfony\Component\DependencyInjection\EnvVarProcessorInterface;

class FalseOrStringEnvVarProcessor implements EnvVarProcessorInterface
{
  public function getEnv(string $prefix, string $name, Closure $getEnv): mixed
  {
    $value = $getEnv($name);
    if ($value === 'false' || $value === false) {
      return false;
    }

    return (string)$value;
  }

  public static function getProvidedTypes(): array
  {
    return [
      'false_or_string' => 'bool|string',
    ];
  }
}
