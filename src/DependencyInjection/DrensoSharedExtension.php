<?php

namespace Drenso\Shared\DependencyInjection;

use Drenso\Shared\Twig\GravatarExtension;
use Exception;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

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
}
