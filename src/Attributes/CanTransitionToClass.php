<?php

declare(strict_types=1);

namespace Thettler\SimpleStates\Attributes;

use Thettler\SimpleStates\TransitionGuard;

#[\Attribute(\Attribute::TARGET_CLASS_CONSTANT)]
class CanTransitionToClass
{
    /**
     * @param  class-string<TransitionGuard>  $class
     */
    public function __construct(
        public string $class
    ) {}
}
