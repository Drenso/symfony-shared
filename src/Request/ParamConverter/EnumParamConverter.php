<?php

namespace Drenso\Shared\Request\ParamConverter;

use BackedEnum;
use LogicException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/** @deprecated Symfony can handle this from version 6.3 */
class EnumParamConverter implements ParamConverterInterface
{
  public function __construct(private readonly array $supportedEnums)
  {
    foreach ($this->supportedEnums as $supportedEnum) {
      if (enum_exists($supportedEnum) && is_a($supportedEnum, BackedEnum::class, true)) {
        continue;
      }

      throw new LogicException(sprintf(
        'Only BackedEnum can be configured for this converter, %s fails this requirement!', $supportedEnum
      ));
    }
  }

  public function apply(Request $request, ParamConverter $configuration): bool
  {
    $name = $configuration->getName();
    if (!$request->attributes->has($name)) {
      return false;
    }

    $requestValue = $request->attributes->get($name);
    if (!$requestValue && $configuration->isOptional()) {
      $request->attributes->set($name, null);

      return true;
    }

    $enumClass = $configuration->getClass();
    if ($requestValue === null || !$value = call_user_func($enumClass . '::tryFrom', $requestValue)) {
      throw new NotFoundHttpException(
        sprintf('Value given for parameter "%s" cannot be converted to Enum "%s".', $name, $enumClass)
      );
    }

    $request->attributes->set($name, $value);

    return true;
  }

  public function supports(ParamConverter $configuration): bool
  {
    return in_array($configuration->getClass(), $this->supportedEnums);
  }
}
