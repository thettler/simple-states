<?php

declare(strict_types=1);

namespace Thettler\SimpleStates\Tests\Fixtures;

enum UnitEnum
{
    use \Thettler\SimpleStates\HasBaseStateFunctions;

    case A;

    public function test()
    {
        $this->getCaseReflection();
    }
}
