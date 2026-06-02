<?php

declare(strict_types=1);

namespace Thettler\SimpleStates\Tests\Fixtures;

use Thettler\SimpleStates\State;
use Thettler\SimpleStates\TransitionGuard;

/**
 * @implements TransitionGuard<BasicTransitionState>
 */
class StateTransitionClass implements TransitionGuard
{
    public function __construct() {}


    public function transitions(\BackedEnum&State $state, ...$params): array
    {
        return [
            BasicTransitionState::Draft,
        ];
    }
}
