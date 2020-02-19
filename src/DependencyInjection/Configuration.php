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
    if (\method_exists($treeBuilder, 'getRootNode')) {
      $rootNode = $treeBuilder->getRootNode();
    } else {
      // BC layer for symfony/config 4.1 and older
      // @phan-suppress-next-line PhanUndeclaredMethod
      $rootNode = $treeBuilder->root('drenso_shared');
    }

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
