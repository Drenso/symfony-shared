<?php

namespace Drenso\Shared\Serializer;

use JMS\Serializer\Construction\ObjectConstructorInterface;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\Metadata\ClassMetadata;
use JMS\Serializer\Visitor\DeserializationVisitorInterface;
use Throwable;

/**
 * See https://stackoverflow.com/questions/31948118/jms-serializer-why-are-new-objects-not-being-instantiated-through-constructor.
 */
class ObjectConstructor implements ObjectConstructorInterface
{
  /**
   * @var ObjectConstructorInterface
   */
  private $inner;

  public function __construct(ObjectConstructorInterface $inner)
  {
    $this->inner = $inner;
  }

  /** {@inheritdoc} */
  public function construct(
      DeserializationVisitorInterface $visitor,
      ClassMetadata $metadata,
      $data,
      array $type,
      DeserializationContext $context): ?object
  {
    try {
      $clazz = $metadata->name;

      return new $clazz();
    } catch (Throwable $e) {
      return $this->inner->construct($visitor, $metadata, $data, $type, $context);
    }
  }
}
