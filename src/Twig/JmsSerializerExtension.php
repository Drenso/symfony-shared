<?php

namespace Drenso\Shared\Twig;

use JMS\Serializer\ContextFactory\SerializationContextFactoryInterface;
use JMS\Serializer\SerializerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class JmsSerializerExtension extends AbstractExtension
{
  /**
   * @var SerializationContextFactoryInterface
   */
  private $contextFactory;
  /**
   * @var SerializerInterface
   */
  private $serializer;

  public function __construct(SerializerInterface $serializer, SerializationContextFactoryInterface $contextFactory)
  {
    $this->serializer     = $serializer;
    $this->contextFactory = $contextFactory;
  }

  public function getFilters()
  {
    return [
        new TwigFilter('jms_json_encode', [$this, 'encode'], ['is_safe' => ['js']]),
    ];
  }

  public function encode($data, ?array $serializationGroups = NULL): string
  {
    $context = $this->contextFactory->createSerializationContext();
    $context->setGroups($serializationGroups ?? ['Default']);

    return $this->serializer->serialize($data, 'json', $context);
  }

}
