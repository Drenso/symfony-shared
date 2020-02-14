<?php

namespace Drenso\Shared\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;

class HiddenEntityType extends AbstractType
{
  public function getParent()
  {
    return EntityType::class;
  }

}
