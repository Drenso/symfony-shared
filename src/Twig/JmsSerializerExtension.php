<?php

namespace Drenso\Shared\Twig;

use JMS\Serializer\ContextFactory\SerializationContextFactoryInterface;
use JMS\Serializer\SerializerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class JmsSerializerExtension extends AbstractExtension
{
  public function __construct(
      private SerializerInterface $serializer,
      private SerializationContextFactoryInterface $contextFactory)
  {
  }

  public function getFilters()
  {
    return [
        new TwigFilter('jms_json_encode', [$this, 'encode'], ['is_safe' => ['js']]),
    ];
  }

  public function encode($data, ?array $serializationGroups = null): string
  {
    $context = $this->contextFactory->createSerializationContext();
    $context->setGroups($serializationGroups ?? ['Default']);

    return $this->serializer->serialize($data, 'json', $context);
  }
}
