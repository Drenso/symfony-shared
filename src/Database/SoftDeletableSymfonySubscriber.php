<?php

namespace Drenso\Shared\Database;

use Doctrine\DBAL\Types\Type;
use Drenso\Shared\Database\Types\DateTimeImmutableWithConversionType;
use Drenso\Shared\Database\Types\UTCDateTimeImmutableWithConversionType;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Request subscriber to register our custom datetime_immutable type as valid for the
 * doctrine extensions soft deletable implementation, and as type for doctrine.
 */
class SoftDeletableSymfonySubscriber implements EventSubscriberInterface
{

  /**
   * @var bool
   */
  private $useUtc;

  public function __construct(bool $useUtc)
  {
    $this->useUtc = $useUtc;
  }

  public static function getSubscribedEvents()
  {
    return [
        ConsoleEvents::COMMAND => [
            ['registerConversionType', 200],
        ],
        KernelEvents::REQUEST  => [
            ['registerConversionType', 200],
        ],
    ];
  }

  public function registerConversionType()
  {
    $type = DateTimeImmutableWithConversionType::DATETIME_IMMUTABLE_WITH_CONVERSION;

    // Register the type with doctrine
    if (!Type::hasType($type)) {
      Type::addType($type, $this->useUtc
          ? UTCDateTimeImmutableWithConversionType::class
          : DateTimeImmutableWithConversionType::class);
    }
  }
}
