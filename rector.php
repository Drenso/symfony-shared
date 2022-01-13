<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
  // Configure parameters
  $containerConfigurator->parameters()
      ->set(Option::PATHS, [
          __DIR__ . '/src',
      ]);

  // Define what rule sets will be applied
  // $containerConfigurator->import(LevelSetList::UP_TO_PHP_81);

  // Register single rules
//  $containerConfigurator->services()
//      ->set(TypedPropertyRector::class);
};
