<?php

namespace Drenso\Shared\Exception;

use Exception;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class EntityValidationFailedException extends Exception
{
  /**
   * @var ConstraintViolationListInterface|null
   */
  private $violationList;

  public function __construct(?ConstraintViolationListInterface $violationList = NULL, ?string $message = NULL)
  {
    $this->violationList = $violationList;

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

  /**
   * @return ConstraintViolationListInterface|null
   */
  public function getViolationList(): ?ConstraintViolationListInterface
  {
    return $this->violationList;
  }

}
