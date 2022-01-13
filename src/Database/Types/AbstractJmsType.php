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
   * @param mixed            $value
   * @param AbstractPlatform $platform
   *
   * @return false|mixed|string|null
   * @throws ConversionException
   */
  public function convertToDatabaseValue($value, AbstractPlatform $platform)
  {
    if ($value === NULL || $value === '') {
      return NULL;
    }

    try {
      if (NULL !== $groups = $this->getSerializationGroups()) {
        $context = StaticSerializer::getSerializationContextFactory()->createSerializationContext();
        $context->setGroups($groups);
      }

      return StaticSerializer::getSerializer()->serialize($value, 'json', $context ?? NULL);
    } catch (Exception $e) {
      throw ConversionException::conversionFailedSerialization($value, 'json', $e->getMessage());
    }
  }

  /**
   * @param mixed            $value
   * @param AbstractPlatform $platform
   *
   * @return mixed|null
   * @throws ConversionException
   */
  public function convertToPHPValue($value, AbstractPlatform $platform)
  {
    if ($value === NULL || $value === '') {
      return NULL;
    }

    $value = is_resource($value) ? stream_get_contents($value) : $value;

    try {
      return StaticSerializer::getSerializer()->deserialize($value, $this->getJmsType(), 'json');
    } catch (Exception $e) {
      throw ConversionException::conversionFailed($value, $this->getName());
    }
  }

  /**
   * Overwrite this to enable serialization group support
   *
   * @return array|null
   */
  protected function getSerializationGroups(): ?array
  {
    return NULL;
  }

  abstract protected function getJmsType(): string;
}
