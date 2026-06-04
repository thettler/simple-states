<?php

declare(strict_types=1);

namespace Thettler\SimpleStates\Exceptions;

use Thettler\SimpleStates\State;

class SimpleStateNotAllowedTransitionException extends \Exception
{
    /**
     * @throws SimpleStateNotAllowedTransitionException
     */
    public static function throw(\BackedEnum&State $state, \BackedEnum&State $transitionTo): never
    {
        $stateClass = $state::class;
        $transitionToClass = $transitionTo::class;

        throw new self(
            "State {$stateClass}::{$state->name} is not allowed to transition to {$transitionToClass}::{$transitionTo->name}",
        );
    }
}
