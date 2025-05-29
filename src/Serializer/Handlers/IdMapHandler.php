<?php

namespace Drenso\Shared\Serializer\Handlers;

use Drenso\Shared\IdMap\AbstractIdMap;
use Drenso\Shared\IdMap\IdMap;
use Drenso\Shared\IdMap\UlidMap;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Visitor\DeserializationVisitorInterface;
use JMS\Serializer\Visitor\SerializationVisitorInterface;
use RuntimeException;

class IdMapHandler implements SubscribingHandlerInterface
{
  /** @phpstan-ignore missingType.iterableValue */
  public static function getSubscribingMethods(): array
  {
    $result = [];
    foreach ([IdMap::class, UlidMap::class] as $type) {
      $result[] = [
        'direction' => GraphNavigatorInterface::DIRECTION_SERIALIZATION,
        'type'      => $type,
        'format'    => 'json',
        'method'    => 'serializeJson',
      ];
      $result[] = [
        'direction' => GraphNavigatorInterface::DIRECTION_DESERIALIZATION,
        'type'      => $type,
        'format'    => 'json',
        'method'    => 'deserializeJson',
      ];
    }

    return $result;
  }

  /**
   * @param array{name: string, params: array} $type
   *
   * @phpstan-ignore missingType.generics,missingType.iterableValue
   */
  public function serializeJson(
    SerializationVisitorInterface $visitor,
    AbstractIdMap $data,
    array $type,
    SerializationContext $context): mixed
  {
    // We change the base type, and pass through possible parameters.
    $type['name'] = 'array';

    // Use the normal array handling
    $context->stopVisiting($data);
    $result = $visitor->visitArray($data->toArray(), $type);
    $context->startVisiting($data);

    // Cast as object to enforce object serialization
    return (object)$result;
  }

  /**
   * @param array{name: string, params: array} $type
   *
   * @phpstan-ignore missingType.generics,missingType.iterableValue
   */
  public function deserializeJson(
    DeserializationVisitorInterface $visitor,
    mixed $data,
    array $type,
    DeserializationContext $context): AbstractIdMap
  {
    $mapType      = $type['name'];
    $type['name'] = 'array';
    $deserialized = $visitor->visitArray($data, $type);

    return match ($mapType) {
      IdMap::class   => IdMap::fromMappedArray($deserialized),
      UlidMap::class => UlidMap::fromMappedArray($deserialized),
      default        => throw new RuntimeException(sprintf(
        'Invalid type configuration, got %s but expected one of (%s, %s)',
        $mapType, IdMap::class, UlidMap::class,
      )),
    };
  }
}
