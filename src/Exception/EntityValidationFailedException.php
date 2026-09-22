<?php

namespace Drenso\Shared\Exception;

use Exception;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class EntityValidationFailedException extends Exception
{
  public function __construct(private ?ConstraintViolationListInterface $violationList = null, ?string $message = null)
  {
    if ($violationList !== null) {
      $messages = [];
      foreach ($violationList as $violation) {
        $messages[] = $violation->getMessage();
      }

      parent::__construct(join("\n", $messages));
    } else {
      parent::__construct($message ?? '');
    }
  }

  public function getViolationList(): ?ConstraintViolationListInterface
  {
    return $this->violationList;
  }

  /** Create a validation failed exception which can be used in the general form error component. */
  public static function create(
    string $message,
    mixed $invalidValue = null,
    string $propertyPath = '',
  ): EntityValidationFailedException {
    return new EntityValidationFailedException(
      new ConstraintViolationList([
        new ConstraintViolation(
          $message,
          null,
          [],
          null,
          $propertyPath,
          $invalidValue,
        ),
      ],
      ));
  }
}
