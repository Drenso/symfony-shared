<?php

namespace Drenso\Shared\DependencyInjection;

use Drenso\Shared\Exception\Handler\EntityValidationFailedExceptionHandler;
use Drenso\Shared\Helper\SpreadsheetHelper;
use Drenso\Shared\Database\SoftDeletableSubscriber;
use Drenso\Shared\Email\EmailService;
use Drenso\Shared\Serializer\Handlers\DecimalHandler;
use Drenso\Shared\Twig\GravatarExtension;
use Exception;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
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

    // Configure the services with retrieved configuration values
    $this->configureApiServices($container, $config);
    $this->configureDatabase($container, $config);
    $this->configureEmailService($container, $config);
    $this->configureSerializer($container, $config);
    $this->configureServices($container, $config);
  }

  /**
   * Configure the API services
   *
   * @param ContainerBuilder $container
   * @param array            $config
   */
  private function configureApiServices(ContainerBuilder $container, array $config): void
  {
    $config = $config['api'];

    if ($config['convert_entity_validation_exception']['enabled']) {
      $container
          ->autowire(EntityValidationFailedExceptionHandler::class)
          ->setAutoconfigured(true)
          ->setArgument('$controllerPrefix', $config['convert_entity_validation_exception']['controller_prefix']);
    }
  }

  /**
   * Configure the Database extension
   *
   * @param ContainerBuilder $container
   * @param array            $config
   */
  private function configureDatabase(ContainerBuilder $container, array $config): void
  {
    $database = $config['database'];
    if ($database['softdeleteable']['enabled']) {
      $container
          ->autowire(SoftDeletableSubscriber::class)
          ->addTag('doctrine.event_subscriber', [
              'connection' => 'default',
          ]);
    }
  }

  /**
   * Configure the e-mail service
   *
   * @param ContainerBuilder $container
   * @param array            $config
   */
  private function configureEmailService(ContainerBuilder $container, array $config): void
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
          ->setLazy(true)
          ->setArgument('$senderEmail', $mailer['sender_email'])
          ->setArgument('$senderName', $mailer['sender_name']);

      if (!$mailer['translate_sender_name']) {
        $definition->setArgument('$translator', NULL);
      }
    }
  }

  /**
   * Configure the services in the bundle
   *
   * @param ContainerBuilder $container
   * @param array            $config
   */
  private function configureSerializer(ContainerBuilder $container, array $config): void
  {
    $serializer = $config['serializer'];

    if ($serializer['decimal_handler']['enabled']) {
      $container
          ->autowire(DecimalHandler::class)
          ->setAutoconfigured(true);
    }
  }

  /**
   * Configure the services in the bundle
   *
   * @param ContainerBuilder $container
   * @param array            $config
   */
  private function configureServices(ContainerBuilder $container, array $config): void
  {
    $services = $config['services'];

    if ($services['gravatar']['enabled']) {
      $container
          ->autowire(GravatarExtension::class)
          ->setAutoconfigured(true)
          ->setArgument('$fallbackStyle', $services['gravatar']['fallback_style']);
    }

    if ($services['spreadsheethelper']['enabled']) {
      $container->autowire(SpreadsheetHelper::class);
    }
  }
}
