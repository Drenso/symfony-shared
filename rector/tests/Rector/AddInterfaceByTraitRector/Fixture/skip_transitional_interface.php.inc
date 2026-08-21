<?php

namespace Drenso\Shared\Rector\Tests\Rector\AddInterfaceByTraitRector\Fixture;

use Drenso\Shared\Rector\Tests\Rector\AddInterfaceByTraitRector\Source\AnotherTrait;
use Drenso\Shared\Rector\Tests\Rector\AddInterfaceByTraitRector\Source\MiddleInterface;

final class SkipTransitionalInterface implements MiddleInterface
{
    use AnotherTrait;
}
