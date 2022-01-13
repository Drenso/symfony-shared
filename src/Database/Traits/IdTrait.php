<?php

namespace Drenso\Shared\Database\Traits;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

trait IdTrait
{
  /**
   * @var int|null
   *
   *
   * @Serializer\Expose()
   */
  #[ORM\Column(name: 'id', type: 'integer')]
  #[ORM\Id]
  #[ORM\GeneratedValue(strategy: 'AUTO')]
  private $id;

  /**
   * @return int|null
   */
  public function getId(): ?int
  {
    return $this->id;
  }
}
