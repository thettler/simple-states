<?php

declare(strict_types=1);

namespace Thettler\SimpleStates\Attributes;

use Thettler\SimpleStates\State;

#[\Attribute(\Attribute::TARGET_METHOD)]
class StateAttributeComputed
{
    public function __construct(
        public string $attribute,
        public \BackedEnum&State $state,
    ) {}
}
