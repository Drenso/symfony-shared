<?php

namespace Drenso\Shared\PhpStan\Rules;

use Doctrine\DBAL\ArrayParameterType;
use Doctrine\ORM\QueryBuilder;
use Drenso\Shared\Interfaces\UlidInterface;
use PhpParser\Node;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\String_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Type\ObjectType;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Uid\Ulid;

/**
 * @implements Rule<MethodCall>
 */
class UlidQueryParameterRule implements Rule
{
  private const string METHOD_NAME   = 'setParameter';
  private const string EXPECTED_HINT = 'ulid';

  public function getNodeType(): string
  {
    return MethodCall::class;
  }

  public function processNode(Node $node, Scope $scope): array
  {
    /** @phpstan-ignore instanceof.alwaysTrue */
    if (!$node instanceof MethodCall) {
      return [];
    }

    if (!$node->name instanceof Identifier) {
      return [];
    }

    if ($node->name->toString() !== self::METHOD_NAME) {
      return [];
    }

    $contextType    = $scope->getType($node->var);
    $isQueryBuilder = (new ObjectType(QueryBuilder::class))->isSuperTypeOf($contextType);
    if (!$isQueryBuilder->yes()) {
      return [];
    }

    // If the second args is an Ulid or has the HasUlidInterface, the third type hint is required
    if (!$arg1 = $node->getArgs()[1] ?? null) {
      // Argument not set, so not possible to check
      return [];
    }

    $method             = QueryBuilder::class . ':' . self::METHOD_NAME;
    $ulidInterfaceClass = UlidInterface::class;
    $line               = $arg1->getStartLine();
    $hint               = UlidType::class . '::NAME';
    $arrayHint          = ArrayParameterType::class . '::BINARY';
    $isArray            = false;

    // Retrieve the passed type
    $passedType = $scope->getType($arg1->value);
    if ($passedType->isArray()->yes()) {
      $isArray    = true;
      $passedType = $passedType->getIterableValueType();
    }

    $isHasUlid = (new ObjectType($ulidInterfaceClass))->isSuperTypeOf($passedType);
    if ($isHasUlid->yes()) {
      // When it implements the HasUlidInterface, it is required to use the ->isUlid() method.
      return [
        RuleErrorBuilder::message("An object implementing $ulidInterfaceClass has been passed to $method, which will never yield any results")
          ->line($line)
          ->addTip('Add ->getUlid() to the parameter')
          ->identifier('nis.doctrineInvalidUlidInterfaceUsage')
          ->build(),
      ];
    }

    $isUlid = (new ObjectType(Ulid::class))->isSuperTypeOf($passedType);
    if (!$isUlid->yes()) {
      // The passed value is not an Ulid and does not implement HasUlidInterface
      return [];
    }

    if (!$arg2 = $node->getArgs()[2] ?? null) {
      // We are missing the type hint
      if ($isArray) {
        return [
          RuleErrorBuilder::message("An Ulid array type parameter is set with $method but the required $arrayHint type hint is missing")
            ->line($line)
            ->identifier('nis.doctrineArrayUlidHintMissing')
            ->build(),
        ];
      }

      return [
        RuleErrorBuilder::message("An Ulid type parameter is set with $method but the required 'ulid' or $hint type hint is missing")
          ->line($line)
          ->identifier('nis.doctrineUlidHintMissing')
          ->build(),
      ];
    }

    // Validate the type hint
    $arg2Value = $arg2->value;

    if ($isArray) {
      if ($arg2Value instanceof ClassConstFetch
        && $arg2Value->class instanceof Name
        && $arg2Value->class->toString() === ArrayParameterType::class
        && $arg2Value->name instanceof Identifier
        && $arg2Value->name->name === 'BINARY') {
        return [
          RuleErrorBuilder::message("An Ulid array type parameter is set with $method with the required type hint $arrayHint, but the ULID needs to be converted to binary")
            ->line($line)
            ->addTip('Map the Ulids with ->toBinary()')
            ->identifier('nis.doctrineArrayUlidDataIncorrect')
            ->build(),
        ];
      }

      return [
        RuleErrorBuilder::message("An Ulid array type parameter is set with $method but the required type hint has not been set to $arrayHint")
          ->line($line)
          ->identifier('nis.doctrineArrayUlidHintIncorrect')
          ->build(),
      ];
    }

    if ($arg2Value instanceof String_ && $arg2Value->value === self::EXPECTED_HINT) {
      return [];
    }

    if ($arg2Value instanceof ClassConstFetch
      && $arg2Value->class instanceof Name
      && $arg2Value->class->toString() === UlidType::class
      && $arg2Value->name instanceof Identifier
      && $arg2Value->name->name === 'NAME') {
      return [];
    }

    return [
      RuleErrorBuilder::message("An Ulid type parameter is set with $method but the required type hint has not been set to 'ulid' or $hint")
        ->line($line)
        ->identifier('nis.doctrineUlidHintIncorrect')
        ->build(),
    ];
  }
}
