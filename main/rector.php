<?php

declare(strict_types=1);

use Rector\Configuration\RectorConfigBuilder;
use Rector\Doctrine\Set\DoctrineSetList;
use Rector\Php81\Rector\Property\ReadOnlyPropertyRector;
use Rector\Symfony\Symfony61\Rector\Class_\CommandConfigureToAttributeRector;

/** @var RectorConfigBuilder $config */
$config = include __DIR__ . '/../rector.php';
return $config
  ->withSkip([
    ReadOnlyPropertyRector::class,
    CommandConfigureToAttributeRector::class,
  ])
  ->withSets([
    DoctrineSetList::DOCTRINE_CODE_QUALITY,
  ]);
