<?php

namespace Drenso\Shared\Serializer\Handlers;

use Decimal\Decimal;
use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\Visitor\DeserializationVisitorInterface;
use JMS\Serializer\Visitor\SerializationVisitorInterface;

/**
 * Class DecimalSerializer.
 *
 * Custom serializer for the decimal type
 */
class DecimalHandler implements SubscribingHandlerInterface
{
  public static function getSubscribingMethods(): array
  {
    return [
        [
            'direction' => GraphNavigatorInterface::DIRECTION_SERIALIZATION,
            'type'      => Decimal::class,
            'format'    => 'json',
            'method'    => 'serializeJson',
        ],
        [
            'direction' => GraphNavigatorInterface::DIRECTION_DESERIALIZATION,
            'type'      => Decimal::class,
            'format'    => 'json',
            'method'    => 'deserializeJson',
        ],
    ];
  }

  public function serializeJson(
      SerializationVisitorInterface $visitor,
      Decimal $decimal,
      array $type,
      Context $context): mixed
  {
    return $visitor->visitString($decimal->toString(), $type);
  }

  public function deserializeJson(
      DeserializationVisitorInterface $visitor,
                                      $decimalAsString,
      array $type,
      Context $context): Decimal
  {
    // Parse empty strings as zero
    if ($decimalAsString === '') {
      return new Decimal(0);
    }

    return new Decimal((string)$decimalAsString);
  }
}
