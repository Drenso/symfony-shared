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
    $this->configureSerializer($rootNode);
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
              ->arrayNode('softdeleteable')
                ->canBeEnabled()
                ->children()
                  ->arrayNode('use_gedmo_workaround')
                    ->canBeDisabled()
                    ->children()
                      ->booleanNode('use_utc')
                        ->defaultTrue()
                      ->end() // use_utc
                    ->end() // use_gedmo_workaround children
                  ->end() // use_gedmo_workaround
                ->end() // softdelete_enabled children
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
          ->arrayNode('email')
            ->addDefaultsIfNotSet()
            ->children()
              ->arrayNode('mailer')
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
                ->end() // mailer children
              ->end() // mailer
            ->end() // email children
          ->end() // email
        ->end();
  }

  /**
   * Setup configuration for the services in the bundle
   *
   * @param ArrayNodeDefinition $node
   */
  private function configureSerializer(ArrayNodeDefinition $node) {
    $node
        ->children()
          ->arrayNode('serializer')
            ->addDefaultsIfNotSet()
            ->children()
              ->arrayNode('handlers')
                ->addDefaultsIfNotSet()
                ->children()
                  ->arrayNode('decimal')
                    ->canBeEnabled()
                  ->end() // decimal
                ->end() // handlers children
              ->end() // handlers
              ->arrayNode('static_serializer')
                ->canBeEnabled()
              ->end() // decimal_handler
            ->end() // serializer children
          ->end() // services
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
              ->arrayNode('gravatar')
                ->canBeEnabled()
                ->children()
                  ->scalarNode('fallback_style')
                    ->cannotBeEmpty()
                    ->defaultValue('mp')
                  ->end() // fallback_style
                ->end() // gravatar children
              ->end() // gravatar
              ->arrayNode('spreadsheethelper')
                ->canBeEnabled()
              ->end() // spreadsheethelper_enabled
            ->end() // services children
          ->end() // services
        ->end();
  }
}
