<?php


namespace Drenso\Shared\Entity\Traits;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

trait TimestampTrait
{

  /**
   * @var DateTimeInterface
   *
   * @ORM\Column(type="datetime")
   * @Assert\NotNull()
   *
   * @Serializer\Expose()
   */
  private $timestamp;

  /**
   * @return DateTimeInterface|null
   */
  public function getTimestamp(): ?DateTimeInterface
  {
    return $this->timestamp;
  }

  /**
   * @param DateTimeInterface $timestamp
   *
   * @return TimestampTrait
   */
  public function setTimestamp(DateTimeInterface $timestamp): self
  {
    $this->timestamp = $timestamp;

    return $this;
  }

}
