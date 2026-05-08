<?php

namespace Drenso\Shared\PhpStan\Rules;

use PhpParser\Node;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Param;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;

/** @implements Rule<Param> */
class EnforceEnumDatabasePromotedPropertyRule extends AbstractEnforceEnumDatabasePropertyRule implements Rule
{
  public function getNodeType(): string
  {
    return Param::class;
  }

  public function processNode(Node $node, Scope $scope): array
  {
    if (!$scope->isInClass() || !$node->isPromoted()) {
      return [];
    }

    if (!$node->var instanceof Variable || !is_string($node->var->name)) {
      return [];
    }

    return $this->checkProperty($scope->getClassReflection(), $node->var->name);
  }
}
