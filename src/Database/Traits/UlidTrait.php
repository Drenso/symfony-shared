<?php

namespace Drenso\Shared\Database\Traits;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Validator\Constraints as Assert;

/** @phpstan-ignore trait.unused */
trait UlidTrait
{
  use UlidMethodsTrait;

  #[ORM\Id]
  #[ORM\Column(type: UlidType::NAME, unique: true)]
  #[Assert\NotNull]
  #[Serializer\Expose]
  #[Serializer\ReadOnlyProperty]
  protected ?Ulid $ulid = null; // Cannot be readonly, as that crashes doctrine in certain scenarios
}
