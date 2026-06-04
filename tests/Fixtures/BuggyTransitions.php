<?php

declare(strict_types=1);

namespace Thettler\SimpleStates\Tests\Fixtures;

use Thettler\SimpleStates\Attributes\CanTransitionToClass;
use Thettler\SimpleStates\IsState;

enum BuggyTransitions: string implements \Thettler\SimpleStates\State
{
    use IsState;

    #[CanTransitionToClass('I\\DONT\\EXIST')]
    case WrongClass = 'wrong_class';
    #[CanTransitionToClass(StateTransitionFaultyClass::class)]
    case NoInvoke = 'no_invoke';
}
