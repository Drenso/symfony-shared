<?php /** @noinspection PhpFullyQualifiedNameUsageInspection */

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
  // Configure parameters
  $containerConfigurator
      ->parameters()
      ->set(Option::PATHS, [__DIR__ . '/src',])
      ->set(Option::AUTO_IMPORT_NAMES, true);

  // Define what rule sets will be applied
  $containerConfigurator->import(\Rector\Doctrine\Set\DoctrineSetList::ANNOTATIONS_TO_ATTRIBUTES);
  $containerConfigurator->import(\Rector\Symfony\Set\SymfonySetList::ANNOTATIONS_TO_ATTRIBUTES);

  // Register single rules
//  $containerConfigurator
//      ->services()
//      ->set(\Rector\Php74\Rector\Property\TypedPropertyRector::class);
};
