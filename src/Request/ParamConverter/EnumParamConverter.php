<?php

namespace Drenso\Shared\Request\ParamConverter;

use BackedEnum;
use LogicException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

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
    if (!$value = call_user_func($configuration->getClass() . '::tryFrom', $request->attributes->get($name))) {
      return false;
    }

    $request->attributes->set($name, $value);

    return true;
  }

  public function supports(ParamConverter $configuration): bool
  {
    return in_array($configuration->getClass(), $this->supportedEnums);
  }
}
