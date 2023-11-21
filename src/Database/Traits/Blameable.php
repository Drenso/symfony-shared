<?php

namespace Drenso\Shared\Database\Traits;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;

trait Blameable
{
  #[ORM\Column(name: 'created_at', type: Types::DATETIME_IMMUTABLE, nullable: false)]
  #[Serializer\Exclude]
  #[Gedmo\Timestampable(on: 'create')]
  private ?DateTimeImmutable $createdAt = null;

  #[ORM\Column(name: 'created_by', type: Types::STRING, length: 255, nullable: true)]
  #[Serializer\Exclude]
  #[Gedmo\Blameable(on: 'create')]
  private ?string $createdBy = null;

  #[ORM\Column(name: 'updated_at', type: Types::DATETIME_IMMUTABLE, nullable: true)]
  #[Serializer\Exclude]
  #[Gedmo\Timestampable(on: 'update')]
  private ?DateTimeImmutable $updatedAt = null;

  #[ORM\Column(name: 'updated_by', type: Types::STRING, length: 255, nullable: true)]
  #[Serializer\Exclude]
  #[Gedmo\Blameable(on: 'update')]
  private ?string $updatedBy = null;

  /** Get the last update time, which is either creation time or update time. */
  public function getLastUpdated(): DateTimeInterface
  {
    return $this->getUpdatedAt() ?? $this->getCreatedAt();
  }

  /** Get the last updated by, which is either creation by or update by. */
  public function getLastUpdatedBy(): ?string
  {
    return $this->getUpdatedBy() ?? $this->getCreatedBy();
  }

  public function setCreatedAt(DateTimeImmutable $createdAt): self
  {
    $this->createdAt = $createdAt;

    return $this;
  }

  public function getCreatedAt(): ?DateTimeInterface
  {
    return $this->createdAt;
  }

  public function setCreatedBy(?string $createdBy): self
  {
    $this->createdBy = $createdBy;

    return $this;
  }

  public function getCreatedBy(): ?string
  {
    return $this->createdBy;
  }

  public function setUpdatedAt(?DateTimeImmutable $updatedAt): self
  {
    $this->updatedAt = $updatedAt;

    return $this;
  }

  public function getUpdatedAt(): ?DateTimeInterface
  {
    return $this->updatedAt;
  }

  public function setUpdatedBy(?string $updatedBy): self
  {
    $this->updatedBy = $updatedBy;

    return $this;
  }

  public function getUpdatedBy(): ?string
  {
    return $this->updatedBy;
  }
}
