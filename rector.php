<?php

declare(strict_types=1);

use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\Expose;
use Rector\Caching\ValueObject\Storage\FileCacheStorage;
use Rector\Config\RectorConfig;
use Rector\Doctrine\Set\DoctrineSetList;
use Rector\Php74\Rector\Property\TypedPropertyRector;
use Rector\Php80\Rector\Class_\AnnotationToAttributeRector;
use Rector\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector;
use Rector\Php80\ValueObject\AnnotationToAttribute;
use Rector\Php81\Rector\Property\ReadOnlyPropertyRector;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use Rector\Symfony\Set\SymfonyLevelSetList;
use Rector\Symfony\Set\SymfonySetList;

return static function (RectorConfig $rc): void {
  // Enable local rector caching
  $rc->cacheClass(FileCacheStorage::class);
  $rc->cacheDirectory('./var/cache/rector');

  $rc->paths([__DIR__ . '/src',]);
  $rc->parallel(processTimeout: 180, jobSize: 10);
  $rc->importNames();
  $rc->skip([
      ReadOnlyPropertyRector::class,
  ]);

  $rc->import(LevelSetList::UP_TO_PHP_81);
  $rc->import(SetList::TYPE_DECLARATION);
  $rc->import(SymfonyLevelSetList::UP_TO_SYMFONY_60);
  $rc->import(DoctrineSetList::DOCTRINE_CODE_QUALITY);

  $rc->ruleWithConfiguration(AnnotationToAttributeRector::class, [
      new AnnotationToAttribute(Gedmo\Mapping\Annotation\Timestampable::class),
      new AnnotationToAttribute(Gedmo\Mapping\Annotation\Blameable::class),
      new AnnotationToAttribute(Exclude::class),
      new AnnotationToAttribute(Expose::class),
  ]);
  $rc->rule(ClassPropertyAssignToConstructorPromotionRector::class);
};
