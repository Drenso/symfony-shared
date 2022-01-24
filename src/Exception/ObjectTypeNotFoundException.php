<?php

namespace Drenso\Shared\Exception;

use Exception;

class ObjectTypeNotFoundException extends Exception
{
  /**
   * ObjectTypeNotFoundException constructor.
   *
   * @param $type
   */
  public function __construct($type, ?array $types = null)
  {
    parent::__construct(sprintf('The object type "%s" is not valid.', $type) . ($types ? sprintf(' Valid options are %s.', implode(', ', $types)) : ''));
  }
}
