<?php

namespace Drenso\Shared\Serializer;

use JMS\Serializer\EventDispatcher\Events;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\Metadata\StaticPropertyMetadata;
use JMS\Serializer\Visitor\SerializationVisitorInterface;

/**
 * Abstract base class for serializing objects with JMS, which resolves the groups
 * for the current object correctly.
 */
abstract class AbstractObjectSerializer
{
  /**
   * Create the default subscriber
   *
   * @param string $clazz
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

  /**
   * The actual serialization handler, which call the abstract doSerialize method
   *
   * @param ObjectEvent $event
   */
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

    $this->doSerialize($visitor, $groups, $event->getObject());
  }

  /**
   * Add a string property to the serialized object
   *
   * @param SerializationVisitorInterface $visitor
   * @param string                        $prop
   * @param string|null                   $value
   */
  protected function addStringProperty(SerializationVisitorInterface $visitor, string $prop, ?string $value): void
  {
    $visitor->visitProperty(new StaticPropertyMetadata('string', '_' . $prop, NULL), $value);
  }

  /**
   * Add a boolean property to the serialized object
   *
   * @param SerializationVisitorInterface $visitor
   * @param string                        $prop
   * @param bool|null                     $value
   */
  protected function addBoolProperty(SerializationVisitorInterface $visitor, string $prop, ?bool $value): void
  {
    $visitor->visitProperty(new StaticPropertyMetadata('boolean', '_' . $prop, NULL), $value);
  }

  /**
   * @param SerializationVisitorInterface $visitor
   * @param array                         $groups
   * @param                               $object
   */
  protected abstract function doSerialize(SerializationVisitorInterface $visitor, array $groups, $object): void;
}