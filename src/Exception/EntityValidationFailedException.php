<?php

namespace Drenso\Shared\Exception;

use Exception;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class EntityValidationFailedException extends Exception
{
  public function __construct(private ?ConstraintViolationListInterface $violationList = null, ?string $message = null)
  {
    if ($violationList !== null) {
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

  public function getViolationList(): ?ConstraintViolationListInterface
  {
    return $this->violationList;
  }
}
