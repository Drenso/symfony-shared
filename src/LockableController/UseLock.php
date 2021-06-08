<?php

namespace Drenso\Shared\LockableController;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationAnnotation;

/**
 * @Annotation
 */
class UseLock extends ConfigurationAnnotation
{
  /**
   * @var string
   * @Required
   */
  private $lockName;

  public function setLockName($lockName)
  {
    $this->lockName = $lockName;
  }

  public function getLockName()
  {
    return $this->lockName;
  }

  public function setValue($value)
  {
    $this->setLockName($value);
  }

  public function getAliasName()
  {
    return 'drenso_use_lock';
  }

  public function allowArray()
  {
    return true;
  }

}
