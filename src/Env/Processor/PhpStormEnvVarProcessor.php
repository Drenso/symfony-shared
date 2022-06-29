<?php

namespace Drenso\Shared\Env\Processor;

use Closure;
use Symfony\Component\DependencyInjection\EnvVarProcessorInterface;

class PhpStormEnvVarProcessor implements EnvVarProcessorInterface
{
  public function getEnv(string $prefix, string $name, Closure $getEnv): mixed
  {
    if (!$project = $getEnv($name)) {
      return null;
    }

    return 'jetbrains://php-storm/navigate/reference?project=' . urlencode((string)$project) . '&path=%%f:%%l&%kernel.project_dir%/>/';
  }

  public static function getProvidedTypes(): array
  {
    return [
        'phpstorm' => 'string',
    ];
  }
}
