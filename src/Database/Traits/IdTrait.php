<?php

namespace Drenso\Shared\Database\Traits;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

trait IdTrait
{
  /**
   * @var int|null
   *
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   *
   * @Serializer\Expose()
   */
  private $id;

  /**
   * @return int|null
   */
  public function getId(): ?int
  {
    return $this->id;
  }
}
