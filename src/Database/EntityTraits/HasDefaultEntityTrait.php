<?php

namespace Drenso\Shared\Database\EntityTraits;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

trait HasDefaultEntityTrait
{

  /**
   * @var boolean
   *
   *
   *
   * @Serializer\Exclude()
   */
  #[ORM\Column(type: 'boolean')]
  #[Assert\NotNull]
  #[Assert\Type(type: 'bool')]
  private $isDefault = false;

  /**
   * @return bool
   */
  public function isDefault(): bool
  {
    return $this->isDefault;
  }

  /**
   * @param bool $isDefault
   *
   * @return $this
   */
  public function setIsDefault(bool $isDefault): self
  {
    $this->isDefault = $isDefault;

    return $this;
  }

}
