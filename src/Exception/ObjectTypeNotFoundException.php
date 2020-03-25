<?php

namespace Drenso\Shared\Exception;

use Exception;

class ObjectTypeNotFoundException extends Exception
{

  /**
   * ObjectTypeNotFoundException constructor.
   *
   * @param            $type
   * @param array|null $types
   */
  public function __construct($type, ?array $types = NULL)
  {
    parent::__construct(sprintf('The object type "%s" is not valid.', $type) . ($types ? sprintf(' Valid options are %s.', implode(', ', $types)) : ''));
  }

}
