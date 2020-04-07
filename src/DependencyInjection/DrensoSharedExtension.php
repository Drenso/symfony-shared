<?php

namespace Drenso\Shared\DependencyInjection;

use Drenso\Shared\Helper\SpreadsheetHelper;
use Drenso\Shared\Database\SoftDeletableSubscriber;
use Drenso\Shared\Email\EmailService;
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
    $this->configureGravatar($container, $config);
    $this->configureDatabase($container, $config);
    $this->configureEmailService($container, $config);
    $this->configureServices($container, $config);
  }

  /**
   * Configure the Gravatar extension
   *
   * @param ContainerBuilder $container
   * @param array            $config
   */
  private function configureGravatar(ContainerBuilder $container, array $config): void
  {
    $definition = $container->getDefinition(GravatarExtension::class);
    $definition->setArgument(0, $config['gravatar']['fallback_style']);
  }

  /**
   * Configure the Database extension
   *
   * @param ContainerBuilder $container
   * @param array            $config
   */
  private function configureDatabase(ContainerBuilder $container, array $config): void
  {
    if ($config['database']['softdelete_enabled']) {
      $container->autowire(SoftDeletableSubscriber::class)->addTag('doctrine.event_subscriber', [
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
    $config = $config['email_service'];

    if ($config['enabled']) {
      if (!class_exists('Symfony\Component\Mailer\Mailer')) {
        throw new InvalidConfigurationException('In order to use the EmailService, the Symfony Mailer component needs to be installed. Try running `composer req symfony/mailer`.');
      }

      if (!$config['sender_email']){
        throw new InvalidConfigurationException('When using the EmaiLService, you need to configure the default sender email (sender_email).');
      }

      $definition = $container->autowire(EmailService::class)
          ->setLazy(true)
          ->setArgument('$senderEmail', $config['sender_email'])
          ->setArgument('$senderName', $config['sender_name']);

      if (!$config['translate_sender_name']) {
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
  private function configureServices(ContainerBuilder $container, array $config): void
  {
    if ($config['services']['spreadsheethelper_enabled']) {
      $container->autowire(SpreadsheetHelper::class);
    }
  }
}
