<?php

namespace App\Serializer\Handlers;

use Decimal\Decimal;
use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\JsonDeserializationVisitor;
use JMS\Serializer\JsonSerializationVisitor;
use JMS\Serializer\Context;


/**
 * Class DecimalSerializer
 *
 * Custom serializer for the decimal type
 */
class DecimalHandler implements SubscribingHandlerInterface
{

  /**
   * @inheritDoc
   */
  public static function getSubscribingMethods()
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

  public function serializeJson(JsonSerializationVisitor $visitor, Decimal $decimal, array $type, Context $context)
  {
    return $visitor->visitString($decimal->toString(), $type);
  }

  public function deserializeJson(JsonDeserializationVisitor $visitor, $decimalAsString, array $type, Context $context)
  {
    // Parse empty strings as zero
    if ($decimalAsString === ""){
      return new Decimal(0);
    }

    return new Decimal($decimalAsString);
  }
}
