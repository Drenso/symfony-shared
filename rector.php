<?php

declare(strict_types=1);

use Rector\Caching\ValueObject\Storage\FileCacheStorage;
use Rector\Config\RectorConfig;
use Rector\Doctrine\Set\DoctrineSetList;
use Rector\Php81\Rector\Property\ReadOnlyPropertyRector;
use Rector\Symfony\CodeQuality\Rector\Class_\EventSubscriberToAttributeRector;
use Rector\Symfony\Symfony73\Rector\Class_\GetFiltersAndFunctionsToAsTwigAttributeRector;

return RectorConfig::configure()
  ->withCache('./var/cache/rector', FileCacheStorage::class)
  ->withPaths(['./src'])
  ->withParallel(timeoutSeconds: 180, jobSize: 10)
  ->withImportNames()
  ->withSkip([
    ReadOnlyPropertyRector::class,
    // Attributes do not make sense for libraries
    GetFiltersAndFunctionsToAsTwigAttributeRector::class,
    EventSubscriberToAttributeRector::class,
  ])
  ->withPhpSets()
  ->withPreparedSets(
    typeDeclarations: true,
  )
  ->withComposerBased(
    symfony: true, // Currently fundamentally broken for libraries, see https://github.com/rectorphp/rector/issues/9858
  )
  ->withSets([
    DoctrineSetList::DOCTRINE_CODE_QUALITY,
  ]);
