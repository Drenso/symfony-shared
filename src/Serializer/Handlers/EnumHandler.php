<?php

namespace Drenso\Shared\Serializer\Handlers;

use BackedEnum;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Visitor\DeserializationVisitorInterface;
use JMS\Serializer\Visitor\SerializationVisitorInterface;

class EnumHandler
{
  public function __construct(private readonly string $enumClass)
  {
  }

  public function serialize(
      SerializationVisitorInterface $visitor,
      BackedEnum $enum,
      array $type,
      SerializationContext $context)
  {
    return $visitor->visitString($enum->value, $type);
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

    return call_user_func($this->enumClass . '::tryFrom', $data);
  }
}
