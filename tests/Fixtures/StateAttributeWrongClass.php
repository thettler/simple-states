<?php

declare(strict_types=1);

namespace Thettler\SimpleStates\Tests\Fixtures;

class StateAttributeWrongClass
{
    public function someMethod(
        BasicState $state,
    ): string {
        return 'green';
    }
}
