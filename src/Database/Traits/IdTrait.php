<?php

namespace Drenso\Shared\Database\Traits;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/** @phpstan-ignore trait.unused */
trait IdTrait
{
  #[ORM\Column(name: 'id', type: Types::INTEGER)]
  #[ORM\Id]
  #[ORM\GeneratedValue(strategy: 'AUTO')]
  #[Serializer\Expose]
  private ?int $id = null;

  public function getId(): ?int
  {
    return $this->id;
  }
}
