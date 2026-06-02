<?php

declare(strict_types=1);

namespace Thettler\SimpleStates\Tests\Fixtures;

use Thettler\SimpleStates\Attributes\CanTransitionToClass;
use Thettler\SimpleStates\IsState;

enum BuggyState: string implements \Thettler\SimpleStates\State
{
    use IsState;

    #[CanTransitionToClass('I\\DONT\\EXIST')]
    case WrongClass = 'wrong_class';

}
