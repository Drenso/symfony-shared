<?php

namespace Drenso\Shared\Database;

use Doctrine\DBAL\Types\Type;
use Drenso\Shared\Database\Types\UTCDateTimeImmutableWithConversionType;
use Gedmo\SoftDeleteable\Mapping\Validator;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Request subscriber to register our custom datetime_immutable type as valid for the
 * doctrine extensions soft deletable implementation, and as type for doctrine.
 */
class SoftDeletableSymfonySubscriber implements EventSubscriberInterface
{

  public static function getSubscribedEvents()
  {
    return [
        KernelEvents::REQUEST => [
            ['registerConversionType', 200],
        ],
    ];
  }

  public function registerConversionType(RequestEvent $event)
  {
    $type = UTCDateTimeImmutableWithConversionType::DATETIME_IMMUTABLE_WITH_CONVERSION;

    // Make sure Gedmo allows our specific type
    Validator::$validTypes[] = $type;

    // Register the type with doctrine
    if (!Type::hasType($type)) {
      Type::addType($type, UTCDateTimeImmutableWithConversionType::class);
    }
  }
}
