<?php

namespace Drenso\Shared\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
  public function getConfigTreeBuilder()
  {
    $treeBuilder = new TreeBuilder('drenso_shared');
    $rootNode    = $treeBuilder->getRootNode();

    // Configure our extensions
    $this->configureGravatar($rootNode);

    return $treeBuilder;
  }

  /**
   * Setup configuration for the gravatar extension
   *
   * @param ArrayNodeDefinition $node
   */
  private function configureGravatar(ArrayNodeDefinition $node)
  {
    $node
        ->children()
          ->arrayNode('gravatar')
            ->addDefaultsIfNotSet()
            ->children()
              ->scalarNode('fallback_style')
                ->cannotBeEmpty()
                ->defaultValue('mp')
              ->end() // fallback_style
            ->end() // gravatar children
          ->end() // gravatar
        ->end();
  }
}
