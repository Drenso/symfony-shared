<?php

namespace Drenso\Shared\Serializer\Handlers;

use BackedEnum;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Visitor\DeserializationVisitorInterface;
use JMS\Serializer\Visitor\SerializationVisitorInterface;

class EnumHandler
{
  public function serialize(
      SerializationVisitorInterface $visitor,
      BackedEnum $enum,
      array $type,
      SerializationContext $context)
  {
    $value = $enum->value;
    if (is_int($value)) {
      return $visitor->visitInteger($value, $type);
    }

    return $visitor->visitString($value, $type);
  }

  public function deserialize(
      DeserializationVisitorInterface $visitor,
      ?string $data,
      array $type,
      DeserializationContext $context): ?BackedEnum
  {
    if ($data === null) {
      return null;
    }

    return call_user_func($type['name'] . '::tryFrom', $data);
  }
}
