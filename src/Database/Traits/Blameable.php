<?php

namespace Drenso\Shared\Database\Traits;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;

trait Blameable
{
  /**
   * @var DateTimeImmutable $created
   *
   * @Gedmo\Timestampable(on="create")
   *
   */
  #[ORM\Column(name: 'created_at', type: 'datetime_immutable', nullable: false)]
  #[Serializer\Exclude]
  private $createdAt;

  /**
   * @var string|null $createdBy
   *
   * @Gedmo\Blameable(on="create")
   *
   */
  #[ORM\Column(name: 'created_by', type: 'string', length: 255, nullable: true)]
  #[Serializer\Exclude]
  private $createdBy;

  /**
   * @var DateTimeImmutable|null $updatedAt
   *
   * @Gedmo\Timestampable(on="update")
   *
   */
  #[ORM\Column(name: 'updated_at', type: 'datetime_immutable', nullable: true)]
  #[Serializer\Exclude]
  private $updatedAt;

  /**
   * @var string|null $updatedBy
   *
   * @Gedmo\Blameable(on="update")
   *
   */
  #[ORM\Column(name: 'updated_by', type: 'string', length: 255, nullable: true)]
  #[Serializer\Exclude]
  private $updatedBy;

  /**
   * Get the last update time, which is either creation time or update time
   */
  public function getLastUpdated(): DateTimeInterface
  {
    return $this->getUpdatedAt() !== NULL ? $this->getUpdatedAt() : $this->getCreatedAt();
  }

  /**
   * Get the last updated by, which is either creation by or update by
   */
  public function getLastUpdatedBy(): ?string
  {
    return $this->getUpdatedBy() !== NULL ? $this->getUpdatedBy() : $this->getCreatedBy();
  }

  /**
   * Set createdAt
   *
   * @param DateTimeImmutable $createdAt
   *
   * @return $this
   */
  public function setCreatedAt(DateTimeImmutable $createdAt): self
  {
    $this->createdAt = $createdAt;

    return $this;
  }

  /**
   * Get createdAt
   *
   * @return DateTimeInterface|null
   */
  public function getCreatedAt(): ?DateTimeInterface
  {
    return $this->createdAt;
  }

  /**
   * Set createdBy
   *
   * @param string|null $createdBy
   *
   * @return $this
   */
  public function setCreatedBy(?string $createdBy): self
  {
    $this->createdBy = $createdBy;

    return $this;
  }

  /**
   * Get createdBy
   *
   * @return string|null
   */
  public function getCreatedBy(): ?string
  {
    return $this->createdBy;
  }

  /**
   * Set updatedAt
   *
   * @param DateTimeImmutable|null $updatedAt
   *
   * @return $this
   */
  public function setUpdatedAt(?DateTimeImmutable $updatedAt): self
  {
    $this->updatedAt = $updatedAt;

    return $this;
  }

  /**
   * Get updatedAt
   *
   * @return DateTimeInterface|null
   */
  public function getUpdatedAt(): ?DateTimeInterface
  {
    return $this->updatedAt;
  }

  /**
   * Set updatedBy
   *
   * @param string|null $updatedBy
   *
   * @return $this
   */
  public function setUpdatedBy(?string $updatedBy): self
  {
    $this->updatedBy = $updatedBy;

    return $this;
  }

  /**
   * Get updatedBy
   *
   * @return string|null
   */
  public function getUpdatedBy(): ?string
  {
    return $this->updatedBy;
  }
}
