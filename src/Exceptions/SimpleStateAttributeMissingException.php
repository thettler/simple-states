<?php

declare(strict_types=1);

namespace Thettler\SimpleStates\Exceptions;

use Thettler\SimpleStates\State;

class SimpleStateAttributeMissingException extends \Exception
{
    /**
     * @throws SimpleStateAttributeMissingException
     */
    public static function throw(\BackedEnum&State $state, string $attribute): never
    {
        $stateClass = $state::class;
        throw new self("State {$stateClass}::{$state->name} is missing attribute \"{$attribute}\"");
    }
}
