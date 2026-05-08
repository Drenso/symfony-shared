<?php

namespace Drenso\Shared\PhpStan\Rules;

use PhpParser\Node;
use PhpParser\Node\Stmt\Property;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;

/** @implements Rule<Property> */
class EnforceEnumDatabasePropertyRule extends AbstractEnforceEnumDatabasePropertyRule implements Rule
{
  public function getNodeType(): string
  {
    return Property::class;
  }

  public function processNode(Node $node, Scope $scope): array
  {
    if (!$scope->isInClass()) {
      return [];
    }

    return $this->checkProperty($scope->getClassReflection(), $node->props[0]->name->toString());
  }
}
