<?php

namespace Drenso\Shared\Database;

use Drenso\Shared\DependencyInjection\DrensoSharedExtension;
use Exception;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DisablePostFlushDetachCompilerPass implements CompilerPassInterface
{
  private const TAG_NAME = 'doctrine.event_listener';

  public function process(ContainerBuilder $container): void
  {
    if (!$container->hasParameter(DrensoSharedExtension::SOFTDELETABLE_DISABLE_POST_FLUSH_DETACH)
      || !$container->getParameter(DrensoSharedExtension::SOFTDELETABLE_DISABLE_POST_FLUSH_DETACH)) {
      return;
    }

    $definition = $container->getDefinition('stof_doctrine_extensions.listener.softdeleteable');
    if (!$definition->hasTag(self::TAG_NAME)) {
      throw new Exception('Doctrine event listener not configured?');
    }

    // Remove the postFlush tag
    $tags                 = $definition->getTags();
    $tags[self::TAG_NAME] = array_filter(
      $tags[self::TAG_NAME],
      static fn (array $tag): bool => ($tag['event'] ?? null) !== 'postFlush'
    );

    // Update the listener tags
    $definition->clearTags();
    $definition->setTags($tags);
  }
}
