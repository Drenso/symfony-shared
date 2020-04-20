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
    $this->configureApiServices($rootNode);
    $this->configureDatabase($rootNode);
    $this->configureEmailService($rootNode);
    $this->configureGravatar($rootNode);
    $this->configureServices($rootNode);

    return $treeBuilder;
  }

  private function configureApiServices(ArrayNodeDefinition $node)
  {
    $node
        ->children()
          ->arrayNode('api')
            ->addDefaultsIfNotSet()
            ->children()
              ->arrayNode('convert_entity_validation_exception')
                ->canBeEnabled()
                ->children()
                  ->scalarNode('controller_prefix')
                    ->defaultValue('App\\Controller\\Api\\')
                  ->end() // sender_email
                ->end() // convert_entity_validation_exception children
              ->end() // convert_entity_validation_exception
            ->end() // api children
          ->end() // api
        ->end();
  }

  /**
   * Setup configuration for the database extensions
   *
   * @param ArrayNodeDefinition $node
   */
  private function configureDatabase(ArrayNodeDefinition $node) {
    $node
        ->children()
          ->arrayNode('database')
            ->addDefaultsIfNotSet()
            ->children()
              ->booleanNode('softdelete_enabled')
                ->defaultFalse()
              ->end() // softdelete_enabled
            ->end() // database children
          ->end() // database
        ->end();
  }

  /**
   * Setup configuration for the mailer extension
   *
   * @param ArrayNodeDefinition $node
   */
  private function configureEmailService(ArrayNodeDefinition $node) {
    $node
        ->children()
          ->arrayNode('email_service')
            ->addDefaultsIfNotSet()
            ->canBeEnabled()
            ->children()
              ->scalarNode('sender_email')
                ->defaultNull()
              ->end() // sender_email
              ->scalarNode('sender_name')
                ->defaultNull()
              ->end() // sender_name
              ->booleanNode('translate_sender_name')
                ->defaultTrue()
              ->end() // translate_sender_name
            ->end() // database children
          ->end() // database
        ->end();
  }

  /**
   * Setup configuration for the gravatar extension
   *
   * @param ArrayNodeDefinition $node
   */
  private function configureGravatar(ArrayNodeDefinition $node) {
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

  /**
   * Setup configuration for the services in the bundle
   *
   * @param ArrayNodeDefinition $node
   */
  private function configureServices(ArrayNodeDefinition $node) {
    $node
        ->children()
          ->arrayNode('services')
            ->addDefaultsIfNotSet()
            ->children()
              ->booleanNode('spreadsheethelper_enabled')
                ->defaultFalse()
              ->end() // spreadsheethelper_enabled
            ->end() // services children
          ->end() // services
        ->end();
  }
}
