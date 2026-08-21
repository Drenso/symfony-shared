<?php

namespace Drenso\Shared\Rector\Tests\Rector\AddInterfaceByTraitRector\Fixture;

use Drenso\Shared\Rector\Tests\Rector\AddInterfaceByTraitRector\Source\SomeTrait;

class SomeClass
{
    use SomeTrait;
}

?>
-----
<?php

namespace Drenso\Shared\Rector\Tests\Rector\AddInterfaceByTraitRector\Fixture;

use Drenso\Shared\Rector\Tests\Rector\AddInterfaceByTraitRector\Source\SomeTrait;

class SomeClass implements \Drenso\Shared\Rector\Tests\Rector\AddInterfaceByTraitRector\Source\SomeInterface
{
    use SomeTrait;
}

?>
