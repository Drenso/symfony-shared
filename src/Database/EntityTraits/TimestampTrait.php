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
   *
   *
   * @Serializer\Expose()
   */
  #[ORM\Column(type: 'datetime_immutable')]
  #[Assert\NotNull]
  private $timestamp;

  /**
   * @return DateTimeInterface|null
   */
  public function getTimestamp(): ?DateTimeInterface
  {
    return $this->timestamp;
  }

  /**
   * @param DateTimeImmutable $timestamp
   *
   * @return TimestampTrait
   */
  public function setTimestamp(DateTimeImmutable $timestamp): self
  {
    $this->timestamp = $timestamp;

    return $this;
  }

}
