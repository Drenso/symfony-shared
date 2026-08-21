<?php

namespace Drenso\Shared\Rector\AddInterfaceByTraitRector\Tests\Fixture;

use Drenso\Shared\Rector\AddInterfaceByTraitRector\Tests\Source\SomeTrait;

class SomeClass
{
    use SomeTrait;
}

?>
-----
<?php

namespace Drenso\Shared\Rector\AddInterfaceByTraitRector\Tests\Fixture;

use Drenso\Shared\Rector\AddInterfaceByTraitRector\Tests\Source\SomeTrait;

class SomeClass implements \Drenso\Shared\Rector\AddInterfaceByTraitRector\Tests\Source\SomeInterface
{
    use SomeTrait;
}

?>
