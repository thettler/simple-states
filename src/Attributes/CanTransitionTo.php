<?php

declare(strict_types=1);

namespace Thettler\SimpleStates\Attributes;

use Thettler\SimpleStates\State;

#[\Attribute(\Attribute::TARGET_CLASS_CONSTANT)]
class CanTransitionTo
{
    /** @var (\BackedEnum&State)[] */
    public array $states;

    public function __construct(\BackedEnum&State ...$states)
    {
        $this->states = $states;
    }
}
