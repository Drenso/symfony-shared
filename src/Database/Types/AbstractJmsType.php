<?php

namespace Drenso\Shared\Database\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Exception\SerializationFailed;
use Doctrine\DBAL\Types\Exception\ValueNotConvertible;
use Doctrine\DBAL\Types\JsonType;
use Drenso\Shared\Serializer\StaticSerializer;
use JMS\Serializer\Exception\Exception;

abstract class AbstractJmsType extends JsonType
{
  /** @throws SerializationFailed */
  public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
  {
    if ($value === null || $value === '') {
      return null;
    }

    try {
      if (null !== $groups = $this->getSerializationGroups()) {
        $context = StaticSerializer::getContextFactory()->createSerializationContext();
        $context->setGroups($groups);
      }

      return StaticSerializer::getSerializer()->serialize($value, 'json', $context ?? null);
    } catch (Exception $e) {
      throw SerializationFailed::new($value, 'json', $e->getMessage());
    }
  }

  /** @throws ValueNotConvertible|\Doctrine\DBAL\Exception */
  public function convertToPHPValue(mixed $value, AbstractPlatform $platform): mixed
  {
    if ($value === null || $value === '') {
      return null;
    }

    $value = is_resource($value) ? stream_get_contents($value) : $value;

    try {
      return StaticSerializer::getSerializer()->deserialize($value, $this->getJmsType(), 'json');
    } catch (Exception $e) {
      throw ValueNotConvertible::new($value, static::class, null, $e);
    }
  }

  /** Overwrite this to enable serialization group support. */
  protected function getSerializationGroups(): ?array
  {
    return null;
  }

  abstract protected function getJmsType(): string;
}
