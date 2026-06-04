<?php

declare(strict_types=1);

namespace Thettler\SimpleStates\Tests\Fixtures;

use Thettler\SimpleStates\IsState;
use Thettler\SimpleStates\State;

class NotABackedEnum implements State
{
    use IsState;
}
