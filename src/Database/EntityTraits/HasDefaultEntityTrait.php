<?php

namespace Drenso\Shared\Database\EntityTraits;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

trait HasDefaultEntityTrait
{
  #[ORM\Column(type: Types::BOOLEAN)]
  #[Assert\NotNull]
  #[Assert\Type(type: 'bool')]
  #[Serializer\Exclude]
  private ?bool $isDefault = false;

  public function isDefault(): bool
  {
    return $this->isDefault;
  }

  public function setIsDefault(bool $isDefault): self
  {
    $this->isDefault = $isDefault;

    return $this;
  }
}
