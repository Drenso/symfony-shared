<?php

namespace Drenso\Shared\Database\EntityTraits;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

trait TimestampTrait
{
  #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
  #[Assert\NotNull]
  #[Serializer\Expose]
  private ?DateTimeImmutable $timestamp = null;

  public function getTimestamp(): ?DateTimeInterface
  {
    return $this->timestamp;
  }

  public function setTimestamp(DateTimeImmutable $timestamp): self
  {
    $this->timestamp = $timestamp;

    return $this;
  }
}
