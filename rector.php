<?php

declare(strict_types=1);

use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\Expose;
use Rector\Config\RectorConfig;
use Rector\Doctrine\Set\DoctrineSetList;
use Rector\Php74\Rector\Property\TypedPropertyRector;
use Rector\Php80\Rector\Class_\AnnotationToAttributeRector;
use Rector\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector;
use Rector\Php80\ValueObject\AnnotationToAttribute;
use Rector\Php81\Rector\Property\ReadOnlyPropertyRector;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Symfony\Set\SymfonySetList;

return static function (RectorConfig $rc): void {
  $rc->paths([__DIR__ . '/src',]);
  $rc->importNames();
  $rc->skip([
      ReadOnlyPropertyRector::class,
  ]);

  $rc->import(LevelSetList::UP_TO_PHP_81);
  $rc->import(DoctrineSetList::ANNOTATIONS_TO_ATTRIBUTES);
  $rc->import(SymfonySetList::ANNOTATIONS_TO_ATTRIBUTES);

  $rc->ruleWithConfiguration(AnnotationToAttributeRector::class, [
      new AnnotationToAttribute(Gedmo\Mapping\Annotation\Timestampable::class),
      new AnnotationToAttribute(Gedmo\Mapping\Annotation\Blameable::class),
      new AnnotationToAttribute(Exclude::class),
      new AnnotationToAttribute(Expose::class),
  ]);
  $rc->rule(TypedPropertyRector::class);
  $rc->rule(ClassPropertyAssignToConstructorPromotionRector::class);
};
