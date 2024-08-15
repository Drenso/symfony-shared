<?php

namespace Drenso\Shared\Serializer\Handlers;

use Drenso\Shared\IdMap\IdMap;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Visitor\DeserializationVisitorInterface;
use JMS\Serializer\Visitor\SerializationVisitorInterface;

class IdMapHandler implements SubscribingHandlerInterface
{
  public static function getSubscribingMethods(): array
  {
    return [
      [
        'direction' => GraphNavigatorInterface::DIRECTION_SERIALIZATION,
        'type'      => IdMap::class,
        'format'    => 'json',
        'method'    => 'serializeJson',
      ],
      [
        'direction' => GraphNavigatorInterface::DIRECTION_DESERIALIZATION,
        'type'      => IdMap::class,
        'format'    => 'json',
        'method'    => 'deserializeJson',
      ],
    ];
  }

  public function serializeJson(
    SerializationVisitorInterface $visitor,
    IdMap $data,
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

  public function deserializeJson(
    DeserializationVisitorInterface $visitor,
    mixed $data,
    array $type,
    DeserializationContext $context): IdMap
  {
    $type['name'] = 'array';

    return IdMap::fromMappedArray($visitor->visitArray($data, $type));
  }
}
