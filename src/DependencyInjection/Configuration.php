<?php

namespace Drenso\Shared\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
  public function getConfigTreeBuilder(): TreeBuilder
  {
    $treeBuilder = new TreeBuilder('drenso_shared');
    if (\method_exists($treeBuilder, 'getRootNode')) {
      $rootNode = $treeBuilder->getRootNode();
    } else {
      // BC layer for symfony/config 4.1 and older
      /** @noinspection PhpUndefinedMethodInspection */
      // @phan-suppress-next-line PhanUndeclaredMethod
      $rootNode = $treeBuilder->root('drenso_shared');
    }

    // Configure global options
    $rootNode->children()->booleanNode('public_services')->defaultFalse();

    // Configure our extensions
    $this->configureApiServices($rootNode);
    $this->configureCommands($rootNode);
    $this->configureDatabase($rootNode);
    $this->configureEmailService($rootNode);
    $this->configureFormExtensions($rootNode);
    $this->configureRequestExtensions($rootNode);
    $this->configureSerializer($rootNode);
    $this->configureServices($rootNode);

    return $treeBuilder;
  }

  /** Setup configuration for the API services in the bundle. */
  private function configureApiServices(ArrayNodeDefinition $node): void
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
                  ->end() // controller_prefix
                  ->scalarNode('data_field')
                    ->defaultValue('payload')
                    ->cannotBeEmpty()
                    ->validate()
                      ->ifInArray(['reason'])->thenInvalid('Invalid data field name %s')
                    ->end() // data_field validator
                  ->end() // data_field
                ->end() // convert_entity_validation_exception children
              ->end() // convert_entity_validation_exception
            ->end() // api children
          ->end() // api
        ->end();
  }

  /** Setup configuration for the commands in the bundle. */
  private function configureCommands(ArrayNodeDefinition $node): void
  {
    $node
        ->children()
          ->arrayNode('commands')
            ->addDefaultsIfNotSet()
            ->children()
              ->arrayNode('check_action_security')
                ->canBeDisabled()
                ->children()
                  ->arrayNode('excluded_controllers')
                    ->scalarPrototype()->end()
                  ->end() // excluded_methods
                ->end() // check_action_security children
              ->end() // check_action_security
            ->end() // commands children
          ->end() // commands
        ->end();
  }

  /** Setup configuration for the database extensions. */
  private function configureDatabase(ArrayNodeDefinition $node): void
  {
    $node
        ->children()
          ->arrayNode('database')
            ->addDefaultsIfNotSet()
            ->children()
              ->arrayNode('softdeletable')
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

  /** Setup configuration for the mailer extension. */
  private function configureEmailService(ArrayNodeDefinition $node): void
  {
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

  /** Setup configuration for the mailer extension. */
  private function configureFormExtensions(ArrayNodeDefinition $node): void
  {
    $node
        ->children()
          ->arrayNode('form_extensions')
            ->addDefaultsIfNotSet()
            ->children()
              ->arrayNode('generic')
                ->canBeDisabled()
              ->end() // generic
              ->arrayNode('button')
                ->canBeDisabled()
              ->end() // button
              ->arrayNode('select2')
                ->canBeDisabled()
              ->end() // select2
            ->end() // form_extensions children
          ->end() // form_extensions
        ->end();
  }

  /** Setup configuration for the request extensions */
  private function configureRequestExtensions(ArrayNodeDefinition $node): void
  {
    $node
        ->children()
          ->arrayNode('request')
            ->addDefaultsIfNotSet()
              ->children()
                ->arrayNode('param_converter')
                  ->canBeEnabled()
                  ->children()
                    ->arrayNode('supported_enums')
                      ->scalarPrototype()->end()
                    ->end() // supported_enums
                  ->end() // param_converter children
                ->end() // param_converter
              ->end() // request children
            ->end() // request
        ->end();
  }

  /** Setup configuration for the services in the bundle. */
  private function configureSerializer(ArrayNodeDefinition $node): void
  {
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
                  ->arrayNode('enum')
                    ->canBeEnabled()
                    ->children()
                      ->arrayNode('supported_enums')
                        ->scalarPrototype()->end()
                      ->end() // supported_enums
                    ->end() // enum children
                  ->end() // enum
                  ->arrayNode('id_map')
                    ->canBeEnabled()
                  ->end() // id_map
                ->end() // handlers children
              ->end() // handlers
              ->arrayNode('static_serializer')
                ->canBeEnabled()
              ->end() // decimal_handler
              ->arrayNode('twig_integration')
                ->canBeEnabled()
              ->end() // twig_integration
            ->end() // serializer children
          ->end() // services
        ->end();
  }

  /** Setup configuration for the services in the bundle. */
  private function configureServices(ArrayNodeDefinition $node): void
  {
    $node
        ->children()
          ->arrayNode('services')
            ->addDefaultsIfNotSet()
            ->children()
              ->arrayNode('feature_flags')
                ->canBeEnabled()
                ->children()
                  ->scalarNode('configuration_file')
                    ->isRequired()
                    ->cannotBeEmpty()
                  ->end() // configuration_file
                  ->scalarNode('configuration_local_file')
                    ->cannotBeEmpty()
                  ->end() // configuration_local_file
                ->end() // feature_flags children
              ->end() // feature_flags
              ->arrayNode('gravatar')
                ->canBeEnabled()
                ->children()
                  ->scalarNode('fallback_style')
                    ->cannotBeEmpty()
                    ->defaultValue('mp')
                  ->end() // fallback_style
                  ->arrayNode('twig_integration')
                    ->canBeDisabled()
                  ->end() // twig_integration
                ->end() // gravatar children
              ->end() // gravatar
              ->arrayNode('ical_provider')
                ->canBeEnabled()
              ->end() // ical_provider
              ->arrayNode('spreadsheethelper')
                ->canBeEnabled()
              ->end() // spreadsheethelper
            ->end() // services children
          ->end() // services
        ->end();
  }
}
