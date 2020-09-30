<?php

namespace Drenso\Shared\DependencyInjection;

use Drenso\Shared\Command\CheckActionSecurityCommand;
use Drenso\Shared\Database\SoftDeletableSubscriber;
use Drenso\Shared\Database\SoftDeletableSymfonyCacheWarmer;
use Drenso\Shared\Database\SoftDeletableSymfonySubscriber;
use Drenso\Shared\Email\EmailService;
use Drenso\Shared\Exception\Handler\EntityValidationFailedExceptionHandler;
use Drenso\Shared\FeatureFlags\FeatureFlags;
use Drenso\Shared\FeatureFlags\RequireFeatureListener;
use Drenso\Shared\Helper\DateTimeProvider;
use Drenso\Shared\Helper\GravatarHelper;
use Drenso\Shared\Helper\SpreadsheetHelper;
use Drenso\Shared\Ical\IcalProvider;
use Drenso\Shared\Serializer\Handlers\DecimalHandler;
use Drenso\Shared\Serializer\StaticSerializer;
use Drenso\Shared\Twig\GravatarExtension;
use Drenso\Shared\Twig\JmsSerializerExtension;
use Exception;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class DrensoSharedExtension extends Extension
{
  /**
   * @inheritDoc
   *
   * @throws Exception
   */
  public function load(array $configs, ContainerBuilder $container)
  {
    // Autoload configured services
    $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
    $loader->load('services.yml');

    // Parse configuration
    $configuration = new Configuration();
    $config        = $this->processConfiguration($configuration, $configs);

    $publicServices = $config['public_services'];

    // Configure the services with retrieved configuration values
    $this->configureApiServices($container, $config, $publicServices);
    $this->configureCommands($container, $config, $publicServices);
    $this->configureDatabase($container, $config, $publicServices);
    $this->configureEmailService($container, $config, $publicServices);
    $this->configureSerializer($container, $config, $publicServices);
    $this->configureServices($container, $config, $publicServices);
  }

  private function configureApiServices(ContainerBuilder $container, array $config, bool $public): void
  {
    $config        = $config['api'];
    $convertConfig = $config['convert_entity_validation_exception'];

    if ($convertConfig['enabled']) {
      $container
          ->autowire(EntityValidationFailedExceptionHandler::class)
          ->setAutoconfigured(true)
          ->setPublic($public)
          ->setArgument('$controllerPrefix', $convertConfig['controller_prefix'])
          ->setArgument('$dataField', $convertConfig['data_field']);
    }
  }

  private function configureCommands(ContainerBuilder $container, array $config, bool $public): void
  {
    $config = $config['commands'];

    if ($config['check_action_security']['enabled']) {
      $container
          ->register(CheckActionSecurityCommand::class)
          ->setAutoconfigured(true)
          ->setPublic($public)
          ->setArgument('$container', new Reference('service_container'))
          ->setArgument('$excludedControllers', $config['check_action_security']['excluded_controllers']);
    }
  }

  private function configureDatabase(ContainerBuilder $container, array $config, bool $public): void
  {
    $database = $config['database'];
    if ($database['softdeletable']['enabled']) {
      if (!class_exists('\Gedmo\SoftDeleteable\SoftDeleteableListener')) {
        throw new InvalidConfigurationException('In order to use softdeletable, DoctrineExtensions must be installed. Try running `composer req stof/doctrine-extensions-bundle`.');
      }

      $container
          ->autowire(SoftDeletableSubscriber::class)
          ->setPublic($public)
          ->addTag('doctrine.event_subscriber', [
              'connection' => 'default',
          ]);

      // Compatibility layer for softdeletable immutable problems
      if ($database['softdeletable']['use_gedmo_workaround']['enabled']) {
        $useUtc = $database['softdeletable']['use_gedmo_workaround']['use_utc'];

        $container
            ->autowire(SoftDeletableSymfonySubscriber::class)
            ->setAutoconfigured(true)
            ->setPublic($public)
            ->setArgument('$useUtc', $useUtc);
        $container
            ->autowire(SoftDeletableSymfonyCacheWarmer::class)
            ->setPublic($public)
            ->addTag('kernel.cache_warmer', ['priority' => 255])
            ->setArgument('$useUtc', $useUtc);
      }
    }
  }

  private function configureEmailService(ContainerBuilder $container, array $config, bool $public): void
  {
    $mailer = $config['email']['mailer'];

    if ($mailer['enabled']) {
      if (!class_exists('Symfony\Component\Mailer\Mailer')) {
        throw new InvalidConfigurationException('In order to use the EmailService, the Symfony Mailer component needs to be installed. Try running `composer req symfony/mailer`.');
      }

      if (!$mailer['sender_email']) {
        throw new InvalidConfigurationException('When using the EmaiLService, you need to configure the default sender email (sender_email).');
      }

      $definition = $container->autowire(EmailService::class)
          ->setPublic($public)
          ->setLazy(true)
          ->setArgument('$senderEmail', $mailer['sender_email'])
          ->setArgument('$senderName', $mailer['sender_name']);

      if (!$mailer['translate_sender_name']) {
        $definition->setArgument('$translator', NULL);
      }
    }
  }

  private function configureSerializer(ContainerBuilder $container, array $config, bool $public): void
  {
    $serializer = $config['serializer'];
    $handlers   = $serializer['handlers'];

    if ($handlers['decimal']['enabled']) {
      $container
          ->autowire(DecimalHandler::class)
          ->setAutoconfigured(true)
          ->setPublic($public);
    }

    if ($serializer['static_serializer']['enabled']) {
      $container
          ->autowire(StaticSerializer::class)
          ->setAutoconfigured(true)
          ->setPublic($public);
    }

    if ($serializer['twig_integration']['enabled']) {
      $container
          ->autowire(JmsSerializerExtension::class)
          ->setAutoconfigured(true)
          ->setPublic($public);
    }
  }

  private function configureServices(ContainerBuilder $container, array $config, bool $public): void
  {
    $services = $config['services'];

    if ($services['feature_flags']['enabled']) {
      $container
          ->autowire(FeatureFlags::class)
          ->setAutoconfigured(true)
          ->setArgument('$configuration', $services['feature_flags']['configuration_file'])
          ->setArgument('$configurationOverride', $services['feature_flags']['configuration_local_file'] ?? '')
          ->setPublic($public);

      $container
          ->autowire(RequireFeatureListener::class)
          ->setAutoconfigured(true)
          ->setPublic($public);
    }

    if ($services['gravatar']['enabled']) {
      $container
          ->autowire(GravatarHelper::class)
          ->setAutoconfigured(true)
          ->setPublic($public)
          ->setArgument('$fallbackStyle', $services['gravatar']['fallback_style']);

      if ($services['gravatar']['twig_integration']){
        $container
            ->autowire(GravatarExtension::class)
            ->setAutoconfigured(true)
            ->setPublic($public);
      }
    }

    if ($services['ical_provider']['enabled']) {
      if (!class_exists('BOMO\IcalBundle\Provider\IcsProvider')) {
        throw new InvalidConfigurationException('In order to use the IcalProvidor, the iCal bundle needs to be installed. Try running `composer req bomo/ical-bundle`.');
      }

      $container
          ->autowire(IcalProvider::class)
          ->setPublic($public)
          ->setArgument('$provider', new Reference('bomo_ical.ics_provider'));
    }

    if ($services['spreadsheethelper']['enabled']) {
      $container
          ->autowire(SpreadsheetHelper::class)
          ->setPublic($public);
    }

    // DateTime provider
    $container->autowire(DateTimeProvider::class)->setPublic($public);
  }
}
