<?php

namespace Drenso\Shared\Serializer;

use JMS\Serializer\EventDispatcher\Events;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\Metadata\PropertyMetadata;
use JMS\Serializer\Metadata\StaticPropertyMetadata;
use JMS\Serializer\Visitor\SerializationVisitorInterface;
use RuntimeException;

/**
 * Abstract base class for serializing objects with JMS, which resolves the groups
 * for the current object correctly.
 */
abstract class AbstractObjectSerializer
{
  /**
   * Create the default subscriber.
   *
   * @return array[]
   */
  public static function defaultSubscriber(string $clazz): array
  {
    return [
        [
            'event'  => Events::POST_SERIALIZE,
            'class'  => $clazz,
            'format' => 'json',
            'method' => 'onPostSerialize',
        ],
    ];
  }

  /** The actual serialization handler, which call the abstract doSerialize method. */
  public function onPostSerialize(ObjectEvent $event): void
  {
    $visitor = $event->getVisitor();
    if (!$visitor instanceof SerializationVisitorInterface) {
      return;
    }

    $context = $event->getContext();

    // Skip when groups are not set
    if (!$context->hasAttribute('groups')) {
      return;
    }

    $groups      = $context->getAttribute('groups');
    $currentPath = $context->getCurrentPath();

    // Determine actual groups for the current path
    foreach ($currentPath as $path) {
      if (!array_key_exists($path, $groups)) {
        break;
      }
      $groups = $groups[$path];
    }

    $this->doSerialize($visitor, $groups, $event->getObject(), $event);
  }

  /** Add a string property to the serialized object. */
  protected function addStringProperty(
      SerializationVisitorInterface $visitor,
      string $prop,
      ?string $value,
      bool $insertUnderscore = true): void
  {
    $visitor->visitProperty(
        new StaticPropertyMetadata('string', $this->propertyName($prop, $insertUnderscore), null),
        $value
    );
  }

  /** Add a boolean property to the serialized object. */
  protected function addBoolProperty(
      SerializationVisitorInterface $visitor,
      string $prop,
      ?bool $value,
      bool $insertUnderscore = true): void
  {
    $visitor->visitProperty(
        new StaticPropertyMetadata('boolean', $this->propertyName($prop, $insertUnderscore), null),
        $value
    );
  }

  protected function addIntProperty(
      SerializationVisitorInterface $visitor,
      string $prop,
      ?int $value,
      bool $insertUnderscore = true): void
  {
    $visitor->visitProperty(
        new StaticPropertyMetadata('int', $this->propertyName($prop, $insertUnderscore), null),
        $value
    );
  }

  /**
   * Add a property as defined in the class metadata.
   * Allows to overwrite the serialized name.
   */
  protected function addProperty(
      SerializationVisitorInterface $visitor,
      ObjectEvent $event,
      string $objectClass,
      string $objectProperty,
      mixed $value,
      ?string $prop = null,
      bool $insertUnderscore = true): void
  {
    $metadata = $event->getContext()->getMetadataFactory()->getMetadataForClass($objectClass)->propertyMetadata[$objectProperty];
    if (!$metadata instanceof PropertyMetadata) {
      throw new RuntimeException('Invalid property metadata!');
    }

    if ($prop) {
      $metadata->serializedName = $this->propertyName($prop, $insertUnderscore);
    }

    $visitor->visitProperty($metadata, $value);
  }

  /** @param $object */
  abstract protected function doSerialize(
      SerializationVisitorInterface $visitor,
      array $groups,
      $object,
      ObjectEvent $event): void;

  private function propertyName(string $prop, bool $insertUnderscore)
  {
    return ($insertUnderscore ? '_' : '') . $prop;
  }
}
