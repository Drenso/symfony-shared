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
    $rootNode    = $treeBuilder->getRootNode();

    // Configure global options
    $rootNode->children()->booleanNode('public_services')->defaultFalse();

    // Configure our extensions
    $this->configureApiServices($rootNode);
    $this->configureCommands($rootNode);
    $this->configureDatabase($rootNode);
    $this->configureEmailService($rootNode);
    $this->configureEnv($rootNode);
    $this->configureFormExtensions($rootNode);
    $this->configureRequestExtensions($rootNode);
    $this->configureSentryTunnel($rootNode);
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
                ->arrayNode('controller_prefix')
                  ->scalarPrototype()->end()
                  ->beforeNormalization()->castToArray()->end()
                  ->defaultValue(['App\\Controller\\Api\\'])
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

  /** Setup configuration for the env helpers in the bundle. */
  private function configureEnv(ArrayNodeDefinition $node): void
  {
    $node
      ->children()
        ->arrayNode('env')
          ->addDefaultsIfNotSet()
          ->children()
            ->arrayNode('processors')
              ->addDefaultsIfNotSet()
              ->children()
                ->arrayNode('phpstorm')
                  ->canBeDisabled()
                  ->end()
                ->end() // phpstorm
              ->end() // processors children
            ->end() // processors
          ->end() // env children
        ->end() // env
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

  /** Setup configuration for a Sentry tunnel */
  private function configureSentryTunnel(ArrayNodeDefinition $node): void
  {
    $node
      ->children()
        ->arrayNode('sentry_tunnel')
          ->addDefaultsIfNotSet()
          ->canBeEnabled()
          ->children()
            ->arrayNode('allowed_dsn')
              ->isRequired()
              ->cannotBeEmpty()
              ->scalarPrototype()->end()
            ->end() // allowed_dsn
            ->integerNode('connect_timeout')
              ->defaultValue(2)
              ->min(1)
              ->max(30)
            ->end() // connect_timeout
            ->integerNode('max_duration')
              ->defaultValue(4)
              ->min(1)
              ->max(30)
            ->end() // max_duration
          ->end() // sentry_tunnel children
        ->end() // sentry_tunnel
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
            ->arrayNode('deserialization')
              ->addDefaultsIfNotSet()
              ->children()
                ->arrayNode('direct_constructor')
                  ->canBeEnabled()
                ->end() // direct_constructor
              ->end() // deserialization children
            ->end() // deserialization
          ->end() // serializer children
        ->end() // serializer
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
                  ->defaultNull()
                ->end() // configuration_file
                ->scalarNode('configuration_local_file')
                  ->defaultNull()
                ->end() // configuration_local_file
                ->scalarNode('custom_handler')
                  ->defaultNull()
                ->end() // custom_handler
              ->end() // feature_flags children
              ->validate()
                ->ifTrue(function (array $value): bool {
                  if (!$value['enabled']) {
                    return false;
                  }

                  if ($value['custom_handler']) {
                    return false;
                  }

                  return !($value['configuration_file'] && $value['configuration_local_file']);
                })
                ->thenInvalid('Either configuration_file and configuration_local_file must be set, or a custom_handler!')
              ->end() // validate
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
            ->arrayNode('spreadsheethelper')
              ->canBeEnabled()
            ->end() // spreadsheethelper
          ->end() // services children
        ->end() // services
      ->end();
  }
}
