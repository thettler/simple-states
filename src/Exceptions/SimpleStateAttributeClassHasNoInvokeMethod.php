<?php

declare(strict_types=1);

namespace Thettler\SimpleStates\Exceptions;

use Thettler\SimpleStates\State;

class SimpleStateAttributeClassHasNoInvokeMethod extends \Exception
{
    /**
     * @throws SimpleStateAttributeClassHasNoInvokeMethod
     */
    public static function throw(\BackedEnum&State $state, string $attribute): never
    {
        $stateClass = $state::class;
        $name = $state->name ?? '';
        throw new self("State {$stateClass}::{$name} with attribute: \"{$attribute}\" has no __invoke method.");
    }
}
