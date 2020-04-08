<?php

namespace Drenso\Shared\Database\Traits;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

trait SoftDeletable
{

  /**
   * @var DateTimeImmutable|null
   * @ORM\Column(name="deleted_at", type="datetime_immutable", nullable=true)
   *
   * @Serializer\Exclude()
   */
  protected $deletedAt;

  /**
   * @var string|null
   * @ORM\Column(name="deleted_by", type="string", length=255, nullable=true)
   *
   * @Serializer\Exclude()
   */
  protected $deletedBy;

  /**
   * Sets deletedAt.
   *
   * @param DateTimeImmutable|null $deletedAt
   *
   * @return $this
   */
  public function setDeletedAt(?DateTimeImmutable $deletedAt = NULL): self
  {
    $this->deletedAt = $deletedAt;

    return $this;
  }

  /**
   * Returns deletedAt.
   *
   * @return DateTimeInterface|null
   */
  public function getDeletedAt(): ?DateTimeInterface
  {
    return $this->deletedAt;
  }

  /**
   * Set deletedBy
   *
   * @param string|null $deletedBy
   *
   * @return $this
   */
  public function setDeletedBy(?string $deletedBy): self
  {
    $this->deletedBy = $deletedBy;

    return $this;
  }

  /**
   * Get deletedBy
   *
   * @return string|null
   */
  public function getDeletedBy(): ?string
  {
    return $this->deletedBy;
  }

  /**
   * Is deleted?
   *
   * @return bool
   */
  public function isDeleted(): bool
  {
    return NULL !== $this->deletedAt;
  }
}
