<?php

declare(strict_types=1);

namespace Thettler\SimpleStates\Tests\Fixtures;

use Thettler\SimpleStates\State;

class StateTransitionFaultyClass
{
    public function someMethod(\BackedEnum&State $state, ...$params): array
    {
        return [
            BasicTransitionState::Draft,
        ];
    }
}
