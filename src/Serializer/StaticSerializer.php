<?php

namespace Drenso\Shared\Serializer;

use JMS\Serializer\ContextFactory\SerializationContextFactoryInterface;
use JMS\Serializer\SerializerInterface;
use RuntimeException;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class StaticSerializer implements EventSubscriberInterface
{
  private static ?SerializerInterface $serializer                                   = null;
  private static ?SerializationContextFactoryInterface $serializationContextFactory = null;

  /**
   * EntitySnapshotter constructor.
   *
   * Makes the static serializer available, as custom doctrine types do not have access to the service container.
   *
   * This also makes sure we share the configuration as configured with our the parameters, so we can leverage
   * automatic doctrine mapping and any custom handlers registered by us or other (for example, PhoneNumber)
   */
  public function __construct(
      SerializerInterface $serializer,
      SerializationContextFactoryInterface $serializationContextFactory)
  {
    self::$serializer                  = $serializer;
    self::$serializationContextFactory = $serializationContextFactory;
  }

  /**
   * {@inheritDoc}
   *
   * We need to use events to trigger this class to be loaded by the container, as otherwise the
   * default behavior would be to only load it when requested by a service or controller, which isn't
   * guaranteed in any other way before Doctrine might load data.
   */
  public static function getSubscribedEvents()
  {
    return [
        KernelEvents::REQUEST => [
            ['onRequest', 255],
        ],
        ConsoleEvents::COMMAND => [
            ['onConsole', 255],
        ],
    ];
  }

  /** @noinspection PhpUnused */
  public function onRequest(RequestEvent $event)
  {
    // Nothing to do, but required to load the static serializer for doctrine
  }

  /** @noinspection PhpUnused */
  public function onConsole(ConsoleCommandEvent $event)
  {
    // Nothing to do, but required to load the static serializer for doctrine
  }

  /**
   * Retrieve the serializer used for the change serialization.
   */
  public static function getSerializer(): SerializerInterface
  {
    if (!self::$serializer) {
      throw new RuntimeException('Serializer not available!');
    }

    return self::$serializer;
  }

  public static function getSerializationContextFactory(): SerializationContextFactoryInterface
  {
    if (!self::$serializationContextFactory) {
      throw new RuntimeException('Serialization context factory not available!');
    }

    return self::$serializationContextFactory;
  }
}
