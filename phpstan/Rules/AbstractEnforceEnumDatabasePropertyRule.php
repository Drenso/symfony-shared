<?php

namespace Drenso\Shared\PhpStan\Rules;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\RuleErrorBuilder;
use ReflectionEnum;

abstract class AbstractEnforceEnumDatabasePropertyRule
{
  /** @return list<IdentifierRuleError> */
  public function checkProperty(ClassReflection $classReflection, string $propertyName): array
  {
    if (!$classReflection->hasNativeProperty($propertyName)) {
      return [];
    }

    $propertyReflection = $classReflection->getNativeProperty($propertyName);
    $propertyType       = $propertyReflection->getNativeType();

    // Check if property type is an enum
    $isEnum = false;
    foreach ($propertyType->getReferencedClasses() as $className) {
      if (!enum_exists($className)) {
        continue;
      }

      if ((new ReflectionEnum($className))->getBackingType()?->getName() !== 'string') {
        continue;
      }

      $isEnum = true;
      break;
    }

    if (!$isEnum) {
      return [];
    }

    // Check for Column attribute
    $hasColumnAttribute   = false;
    $hasCorrectColumnType = false;
    $hasLengthSpecified   = false;

    foreach ($propertyReflection->getAttributes() as $attribute) {
      if ($attribute->getName() !== Column::class) {
        continue;
      }

      // Column attribute has been set, so continue validation
      $hasColumnAttribute = true;

      $args = $attribute->getArgumentTypes();

      // Validate the type value is set to enum
      $type = ($args['type'] ?? null);
      foreach (($type?->getConstantStrings() ?? []) as $constantString) {
        if ($constantString->getValue() !== Types::ENUM) {
          continue;
        }

        $hasCorrectColumnType = true;
      }

      // Validate the length value is not set
      if ($hasCorrectColumnType && ($args['length'] ?? null)) {
        $hasLengthSpecified = true;
      }
    }

    if (!$hasColumnAttribute) {
      return [];
    }

    if (!$hasCorrectColumnType) {
      return [
        RuleErrorBuilder::message(sprintf(
          'Property %s::$%s uses enum type but the #[Column] attribute does not specify type: Types::ENUM.',
          $classReflection->getDisplayName(),
          $propertyName,
        ))
          ->identifier('drensoShared.doctrineEnumColumnMissingEnumType')
          ->build(),
      ];
    } elseif ($hasLengthSpecified) {
      return [
        RuleErrorBuilder::message(sprintf(
          'Property %s::$%s uses enum type but the #[Column] attribute specifies a length.',
          $classReflection->getDisplayName(),
          $propertyName,
        ))
          ->identifier('drensoShared.doctrineEnumColumnLengthSpecified')
          ->build(),
      ];
    }

    return [];
  }
}
