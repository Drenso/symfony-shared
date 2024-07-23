<?php

declare(strict_types=1);

use Rector\Caching\ValueObject\Storage\FileCacheStorage;
use Rector\Config\RectorConfig;
use Rector\Doctrine\Set\DoctrineSetList;
use Rector\Php81\Rector\Property\ReadOnlyPropertyRector;
use Rector\Symfony\Set\SymfonySetList;
use Rector\Symfony\Symfony61\Rector\Class_\CommandConfigureToAttributeRector;

return RectorConfig::configure()
  ->withCache('./var/cache/rector', FileCacheStorage::class)
  ->withPaths([__DIR__ . '/src'])
  ->withParallel(timeoutSeconds: 180, jobSize: 10)
  ->withImportNames()
  ->withSkip([
    ReadOnlyPropertyRector::class,
    CommandConfigureToAttributeRector::class,
  ])
  ->withPhpSets()
  ->withPreparedSets(
    typeDeclarations: true,
  )
  ->withSets([
    DoctrineSetList::DOCTRINE_CODE_QUALITY,
    SymfonySetList::SYMFONY_61,
    SymfonySetList::SYMFONY_62,
    SymfonySetList::SYMFONY_63,
    SymfonySetList::SYMFONY_64,
  ]);
