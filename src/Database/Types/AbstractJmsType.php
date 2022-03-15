<?php

namespace Drenso\Shared\Database\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\JsonType;
use Drenso\Shared\Serializer\StaticSerializer;
use JMS\Serializer\Exception\Exception;

abstract class AbstractJmsType extends JsonType
{
  /**
   * @param mixed $value
   *
   * @throws ConversionException
   *
   * @return false|mixed|string|null
   */
  public function convertToDatabaseValue($value, AbstractPlatform $platform)
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
      throw ConversionException::conversionFailedSerialization($value, 'json', $e->getMessage());
    }
  }

  /**
   * @param mixed $value
   *
   * @throws ConversionException
   *
   * @return mixed|null
   */
  public function convertToPHPValue($value, AbstractPlatform $platform)
  {
    if ($value === null || $value === '') {
      return null;
    }

    $value = is_resource($value) ? stream_get_contents($value) : $value;

    try {
      return StaticSerializer::getSerializer()->deserialize($value, $this->getJmsType(), 'json');
    } catch (Exception) {
      throw ConversionException::conversionFailed($value, $this->getName());
    }
  }

  /** Overwrite this to enable serialization group support. */
  protected function getSerializationGroups(): ?array
  {
    return null;
  }

  abstract protected function getJmsType(): string;
}
