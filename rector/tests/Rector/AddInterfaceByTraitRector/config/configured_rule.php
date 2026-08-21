<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Drenso\Shared\Rector\Tests\Rector\AddInterfaceByTraitRector\Source\AnotherTrait;
use Drenso\Shared\Rector\Tests\Rector\AddInterfaceByTraitRector\Source\SomeInterface;
use Drenso\Shared\Rector\Tests\Rector\AddInterfaceByTraitRector\Source\SomeTrait;
use Drenso\Shared\Rector\Tests\Rector\AddInterfaceByTraitRector\Source\TopMostInterface;
use Drenso\Shared\Rector\Rector\AddInterfaceByTraitRector;

return static function (RectorConfig $rectorConfig): void {
  $rectorConfig
    ->ruleWithConfiguration(AddInterfaceByTraitRector::class, [
      SomeTrait::class => SomeInterface::class,
      AnotherTrait::class => TopMostInterface::class,
    ]);
};
