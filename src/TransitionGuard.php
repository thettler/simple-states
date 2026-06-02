<?php

namespace Thettler\SimpleStates;

/**
 * @template TState of \BackedEnum&State
 */
interface TransitionGuard
{
    /**
     * @param TState $state
     * @return list<\BackedEnum&State>
     */
    public function transitions(\BackedEnum&State $state, ...$params): array;
}
