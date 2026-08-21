<?php

namespace Drenso\Shared\Exception;

use Exception;

class ObjectTypeNotFoundException extends Exception
{
  /**
   * @param string   $type
   * @param string[] $types
   */
  public function __construct(mixed $type, ?array $types = null)
  {
    parent::__construct(sprintf('The object type "%s" is not valid.', $type) . ($types ? sprintf(' Valid options are %s.', implode(', ', $types)) : ''));
  }
}
