<?php

namespace Drenso\Shared\Database\EntityTraits;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

trait TimestampTrait
{
  /**
   * @var DateTimeImmutable
   */
  #[ORM\Column(type: 'datetime_immutable')]
  #[Assert\NotNull]
  #[Serializer\Expose]
  private $timestamp;

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
