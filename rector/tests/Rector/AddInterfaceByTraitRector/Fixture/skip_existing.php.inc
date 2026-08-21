<?php

namespace Drenso\Shared\Rector\Tests\Rector\AddInterfaceByTraitRector\Fixture;

use Drenso\Shared\Rector\Tests\Rector\AddInterfaceByTraitRector\Source\SomeInterface;
use Drenso\Shared\Rector\Tests\Rector\AddInterfaceByTraitRector\Source\SomeTrait;

class SkipExisting implements SomeInterface
{
    use SomeTrait;
}
