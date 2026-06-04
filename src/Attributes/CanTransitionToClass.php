<?php

declare(strict_types=1);

namespace Thettler\SimpleStates\Attributes;

#[\Attribute(\Attribute::TARGET_CLASS_CONSTANT)]
class CanTransitionToClass
{
    /**
     * @param  class-string  $class
     */
    public function __construct(
        public string $class,
    ) {}
}
