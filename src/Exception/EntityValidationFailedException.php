<?php

namespace App\Exception;

use Exception;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class EntityValidationFailedException extends Exception
{
  public function __construct(?ConstraintViolationListInterface $violationList = NULL, ?string $message = NULL)
  {
    if ($violationList !== NULL) {
      $messages = [];
      foreach ($violationList as $violation) {
        assert($violation instanceof ConstraintViolationInterface);
        $messages[] = $violation->getMessage();
      }

      parent::__construct(join("\n", $messages));
    } else {
      parent::__construct($message);
    }
  }
}
