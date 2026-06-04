<?php

declare(strict_types=1);

namespace Thettler\SimpleStates;

/**
 * @phpstan-require-implements State
 */
trait IsState
{
    use HasStateTransitions;
    use HasStateAttributes;
}
