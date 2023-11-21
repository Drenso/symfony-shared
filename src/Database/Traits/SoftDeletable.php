<?php

namespace Drenso\Shared\Database\Traits;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

trait SoftDeletable
{
  #[ORM\Column(name: 'deleted_at', type: 'datetime_immutable_with_conversion', nullable: true)]
  #[Serializer\Exclude]
  protected ?DateTimeImmutable $deletedAt = null;

  #[ORM\Column(name: 'deleted_by', type: Types::STRING, length: 255, nullable: true)]
  #[Serializer\Exclude]
  protected ?string $deletedBy = null;

  public function setDeletedAt(?DateTimeImmutable $deletedAt = null): self
  {
    $this->deletedAt = $deletedAt;

    return $this;
  }

  public function getDeletedAt(): ?DateTimeInterface
  {
    return $this->deletedAt;
  }

  public function setDeletedBy(?string $deletedBy): self
  {
    $this->deletedBy = $deletedBy;

    return $this;
  }

  public function getDeletedBy(): ?string
  {
    return $this->deletedBy;
  }

  public function isDeleted(): bool
  {
    return null !== $this->deletedAt;
  }
}
