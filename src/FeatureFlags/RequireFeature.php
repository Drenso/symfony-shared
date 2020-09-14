<?php

namespace Drenso\Shared\FeatureFlags;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationAnnotation;

/**
 * @Annotation
 */
class RequireFeature extends ConfigurationAnnotation
{
  /**
   * @var string
   */
  private $flag;

  public function setFlag($flag)
  {
    $this->flag = $flag;
  }

  public function getFlag()
  {
    return $this->flag;
  }

  public function setValue($value)
  {
    $this->setFlag($value);
  }

  public function getAliasName()
  {
    return 'drenso_require_feature';
  }

  public function allowArray()
  {
    return true;
  }
}
