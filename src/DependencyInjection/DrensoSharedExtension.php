<?php

namespace Drenso\Shared\DependencyInjection;

use BOMO\IcalBundle\Provider\IcsProvider;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Drenso\Shared\Command\CheckActionSecurityCommand;
use Drenso\Shared\Database\SoftDeletableFilterController;
use Drenso\Shared\Database\SoftDeletableSubscriber;
use Drenso\Shared\Database\SoftDeletableSymfonyCacheWarmer;
use Drenso\Shared\Database\SoftDeletableSymfonySubscriber;
use Drenso\Shared\Email\EmailService;
use Drenso\Shared\Env\Processor\PhpStormEnvVarProcessor;
use Drenso\Shared\Exception\Handler\EntityValidationFailedExceptionHandler;
use Drenso\Shared\FeatureFlags\FeatureFlags;
use Drenso\Shared\FeatureFlags\RequireFeatureListener;
use Drenso\Shared\Form\Extension\ButtonExtension;
use Drenso\Shared\Form\Extension\FormExtension;
use Drenso\Shared\Form\Extension\Select2Extension;
use Drenso\Shared\Form\Type\Select2EntitySearchType;
use Drenso\Shared\Helper\DateTimeProvider;
use Drenso\Shared\Helper\GravatarHelper;
use Drenso\Shared\Helper\SpreadsheetHelper;
use Drenso\Shared\Ical\IcalBuilder;
use Drenso\Shared\Ical\IcalProvider;
use Drenso\Shared\Request\ParamConverter\EnumParamConverter;
use Drenso\Shared\Sentry\SentryTunnelController;
use Drenso\Shared\Serializer\Handlers\DecimalHandler;
use Drenso\Shared\Serializer\Handlers\EnumHandler;
use Drenso\Shared\Serializer\Handlers\IdMapHandler;
use Drenso\Shared\Serializer\ObjectConstructor;
use Drenso\Shared\Serializer\StaticSerializer;
use Drenso\Shared\Twig\GravatarExtension;
use Drenso\Shared\Twig\JmsSerializerExtension;
use Gedmo\SoftDeleteable\SoftDeleteableListener;
use JMS\Serializer\ContextFactory\SerializationContextFactoryInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class DrensoSharedExtension extends ConfigurableExtension
{
  public function loadInternal(array $mergedConfig, ContainerBuilder $container): void
  {
    $publicServices = $mergedConfig['public_services'];

    // Configure the services with retrieved configuration values
    $this->configureApiServices($container, $mergedConfig, $publicServices);
    $this->configureCommands($container, $mergedConfig, $publicServices);
    $this->configureDatabase($container, $mergedConfig, $publicServices);
    $this->configureEmailService($container, $mergedConfig, $publicServices);
    $this->configureEnv($container, $mergedConfig);
    $this->configureFormExtensions($container, $mergedConfig);
    $this->configureRequestExtensions($container, $mergedConfig, $publicServices);
    $this->configureSentryTunnel($container, $mergedConfig);
    $this->configureSerializer($container, $mergedConfig, $publicServices);
    $this->configureServices($container, $mergedConfig, $publicServices);
  }

  private function configureApiServices(ContainerBuilder $container, array $config, bool $public): void
  {
    $config        = $config['api'];
    $convertConfig = $config['convert_entity_validation_exception'];

    if ($convertConfig['enabled']) {
      $container
          ->register(EntityValidationFailedExceptionHandler::class)
          ->setAutoconfigured(true)
          ->setPublic($public)
          ->setArgument('$serializer', new Reference(SerializerInterface::class))
          ->setArgument('$controllerPrefixes', $convertConfig['controller_prefix'])
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
          ->setArgument('$router', new Reference('router'))
          ->setArgument('$excludedControllers', $config['check_action_security']['excluded_controllers']);
    }
  }

  private function configureDatabase(ContainerBuilder $container, array $config, bool $public): void
  {
    $database = $config['database'];
    if ($database['softdeletable']['enabled']) {
      if (!class_exists(SoftDeleteableListener::class)) {
        throw new InvalidConfigurationException('In order to use softdeletable, DoctrineExtensions must be installed. Try running `composer req stof/doctrine-extensions-bundle`.');
      }

      $container
          ->register(SoftDeletableSubscriber::class)
          ->setArgument('$tokenStorage', new Reference(TokenStorageInterface::class))
          ->setPublic($public)
          ->addTag('doctrine.event_subscriber', [
              'connection' => 'default',
          ]);

      $container
          ->register(SoftDeletableFilterController::class)
          ->setArgument('$entityManager', new Reference(EntityManagerInterface::class))
          ->setPublic($public);

      // Compatibility layer for softdeletable immutable problems
      if ($database['softdeletable']['use_gedmo_workaround']['enabled']) {
        $useUtc = $database['softdeletable']['use_gedmo_workaround']['use_utc'];

        $container
            ->register(SoftDeletableSymfonySubscriber::class)
            ->setAutoconfigured(true)
            ->setPublic($public)
            ->setArgument('$useUtc', $useUtc);
        $container
            ->register(SoftDeletableSymfonyCacheWarmer::class)
            ->setPublic($public)
            // This need to be run before any other Doctrine warmer!
            ->addTag('kernel.cache_warmer', ['priority' => 10000])
            ->setArgument('$useUtc', $useUtc);
      }
    }
  }

  private function configureEmailService(ContainerBuilder $container, array $config, bool $public): void
  {
    $mailer = $config['email']['mailer'];

    if ($mailer['enabled']) {
      if (!class_exists(Mailer::class)) {
        throw new InvalidConfigurationException('In order to use the EmailService, the Symfony Mailer component needs to be installed. Try running `composer req symfony/mailer`.');
      }

      if (!$mailer['sender_email']) {
        throw new InvalidConfigurationException('When using the EmaiLService, you need to configure the default sender email (sender_email).');
      }

      $definition = $container
          ->register(EmailService::class)
          ->setPublic($public)
          ->setLazy(true)
          ->setArgument('$mailer', new Reference(MailerInterface::class))
          ->setArgument('$senderEmail', $mailer['sender_email'])
          ->setArgument('$senderName', $mailer['sender_name'])
          ->setArgument('$translator', new Reference(TranslatorInterface::class, ContainerInterface::NULL_ON_INVALID_REFERENCE))
          ->setArgument('$transport', new Reference(TransportInterface::class));

      if (!$mailer['translate_sender_name']) {
        $definition->setArgument('$translator', null);
      }
    }
  }

  private function configureEnv(ContainerBuilder $container, array $config): void
  {
    $processors = $config['env']['processors'];

    if ($processors['phpstorm']['enabled']) {
      $definition = $container
          ->register(PhpStormEnvVarProcessor::class)
          ->addTag('container.env_var_processor');
    }
  }

  private function configureFormExtensions(ContainerBuilder $container, array $config): void
  {
    $form = $config['form_extensions'];
    if ($form['generic']['enabled']) {
      $container
          ->register(FormExtension::class)
          ->setAutoconfigured(true);
    }
    if ($form['button']['enabled']) {
      $container
          ->register(ButtonExtension::class)
          ->setAutoconfigured(true);
    }
    if ($form['select2']['enabled']) {
      $container
          ->register(Select2Extension::class)
          ->setAutoconfigured(true)
          ->setArgument('$translator', new Reference(TranslatorInterface::class, ContainerInterface::NULL_ON_INVALID_REFERENCE));

      $container
          ->register(Select2EntitySearchType::class)
          ->setAutoconfigured(true)
          ->setArgument('$registry', new Reference(ManagerRegistry::class))
          ->setArgument('$propertyAccessor', new Reference(PropertyAccessorInterface::class));
    }
  }

  private function configureRequestExtensions(ContainerBuilder $container, array $config, bool $public): void
  {
    $request = $config['request'];
    if ($request['param_converter']['enabled']) {
      $container
          ->register(EnumParamConverter::class)
          ->setAutoConfigured(true)
          ->setPublic($public)
          ->setArgument('$supportedEnums', $request['param_converter']['supported_enums']);
    }
  }

  private function configureSentryTunnel(ContainerBuilder $container, array $config): void
  {
    $sentryTunnel = $config['sentry_tunnel'];

    if ($sentryTunnel['enabled']) {
      $container
          ->register(SentryTunnelController::class)
          ->setAutoconfigured(true)
          ->addMethodCall('setContainer', [new Reference('service_container')])
          ->addTag('controller.service_arguments')
          ->setArgument('$httpClient', new Reference(HttpClientInterface::class))
          ->setArgument('$allowedDsn', $sentryTunnel['allowed_dsn'])
          ->setArgument('$connectTimeout', $sentryTunnel['connect_timeout'])
          ->setArgument('$maxDuration', $sentryTunnel['max_duration']);
    }
  }

  private function configureSerializer(ContainerBuilder $container, array $config, bool $public): void
  {
    $serializer      = $config['serializer'];
    $handlers        = $serializer['handlers'];
    $deserialization = $serializer['deserialization'];

    if ($handlers['decimal']['enabled']) {
      $container
          ->register(DecimalHandler::class)
          ->setAutoconfigured(true)
          ->setPublic($public);
    }

    if ($handlers['enum']['enabled']) {
      $handlerDefinition = $container->register(EnumHandler::class);
      foreach ($handlers['enum']['supported_enums'] as $enumClass) {
        $handlerDefinition
            ->addTag('jms_serializer.handler', [
                'type'      => $enumClass,
                'direction' => 'serialization',
                'format'    => 'json',
                'method'    => 'serialize',
            ])
            ->addTag('jms_serializer.handler', [
                'type'      => $enumClass,
                'direction' => 'deserialization',
                'format'    => 'json',
                'method'    => 'deserialize',
            ]);
      }
    }

    if ($handlers['id_map']['enabled']) {
      $container
          ->register(IdMapHandler::class)
          ->setAutoconfigured(true)
          ->setPublic($public);
    }

    if ($serializer['static_serializer']['enabled']) {
      $container
          ->register(StaticSerializer::class)
          ->setAutoconfigured(true)
          ->setPublic($public)
          ->setArgument('$serializer', new Reference(SerializerInterface::class))
          ->setArgument('$contextFactory', new Reference(SerializationContextFactoryInterface::class));
    }

    if ($serializer['twig_integration']['enabled']) {
      $container
          ->register(JmsSerializerExtension::class)
          ->setAutoconfigured(true)
          ->setPublic($public)
          ->setArgument('$serializer', new Reference(SerializerInterface::class))
          ->setArgument('$contextFactory', new Reference(SerializationContextFactoryInterface::class));
    }

    if ($deserialization['direct_constructor']['enabled']) {
      $container
          ->register(ObjectConstructor::class)
          ->setDecoratedService(new Reference('jms_serializer.doctrine_object_constructor'))
          ->setArgument('$inner', new Reference('jms_serializer.doctrine_object_constructor.inner'));
    }
  }

  private function configureServices(ContainerBuilder $container, array $config, bool $public): void
  {
    $services = $config['services'];

    if ($services['feature_flags']['enabled']) {
      $featureFlags = $container
          ->register(FeatureFlags::class)
          ->setAutoconfigured(true)
          ->setArgument('$configuration', $services['feature_flags']['configuration_file'])
          ->setArgument('$configurationOverride', $services['feature_flags']['configuration_local_file'] ?? '')
          ->setPublic($public);

      $container
          ->register(RequireFeatureListener::class)
          ->setAutoconfigured(true)
          ->setPublic($public)
          ->setArgument('$featureFlags', $featureFlags);
    }

    if ($services['gravatar']['enabled']) {
      $gravatarHelper = $container
          ->register(GravatarHelper::class)
          ->setAutoconfigured(true)
          ->setPublic($public)
          ->setArgument('$fallbackStyle', $services['gravatar']['fallback_style']);

      if ($services['gravatar']['twig_integration']) {
        $container
            ->register(GravatarExtension::class)
            ->setAutoconfigured(true)
            ->setPublic($public)
            ->setArgument('$gravatarHelper', $gravatarHelper);
      }
    }

    if ($services['ical_provider']['enabled']) {
      trigger_deprecation('drenso/symfony-shared', '2.14', 'The "%s" has been deprecated in favour of "%s"', IcalProvider::class, IcalBuilder::class);

      if (!class_exists(IcsProvider::class)) {
        throw new InvalidConfigurationException('In order to use the IcalProvider, the iCal bundle needs to be installed. Try running `composer req bomo/ical-bundle`.');
      }

      $container
          ->register(IcalProvider::class)
          ->setPublic($public)
          ->setArgument('$provider', new Reference('bomo_ical.ics_provider'));
    }

    if ($services['spreadsheethelper']['enabled']) {
      $container
          ->register(SpreadsheetHelper::class)
          ->setPublic($public)
          ->setArgument('$translator', new Reference(TranslatorInterface::class, ContainerInterface::NULL_ON_INVALID_REFERENCE));
    }

    // DateTime provider
    $container
        ->register(DateTimeProvider::class)
        ->setPublic($public);
  }
}
