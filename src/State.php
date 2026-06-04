<?php

declare(strict_types=1);

namespace Thettler\SimpleStates;

use Thettler\SimpleStates\Exceptions\SimpleStateAttributeClassHasNoInvokeMethod;
use Thettler\SimpleStates\Exceptions\SimpleStateAttributeMissingException;
use Thettler\SimpleStates\Exceptions\SimpleStateMustBeUsedOnBackedEnumException;
use Thettler\SimpleStates\Exceptions\SimpleStateNotAllowedTransitionException;

interface State
{
    /**
     * @template T of (\BackedEnum&State)
     * @param  T  $state
     * @return T
     * @throws SimpleStateNotAllowedTransitionException
     * @throws SimpleStateMustBeUsedOnBackedEnumException
     * @throws \ReflectionException
     */
    public function transitionTo(\BackedEnum&State $state): \BackedEnum&State;

    /**
     * @throws SimpleStateMustBeUsedOnBackedEnumException
     * @throws \ReflectionException
     */
    public function canTransitionTo(\BackedEnum&State $transitionTo, ...$params): bool;

    /**
     * @throws SimpleStateMustBeUsedOnBackedEnumException
     * @throws \ReflectionException
     */
    public function getTransitions(...$params): array;

    /**
     * @throws SimpleStateAttributeMissingException
     * @throws \ReflectionException
     * @throws SimpleStateAttributeClassHasNoInvokeMethod
     * @throws SimpleStateMustBeUsedOnBackedEnumException
     */
    public function getStateAttribute(string $attributeName, ...$params): mixed;
}
