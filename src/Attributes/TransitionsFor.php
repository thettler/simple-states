<?php

declare(strict_types=1);

namespace Thettler\SimpleStates\Attributes;

use Thettler\SimpleStates\State;

#[\Attribute(\Attribute::TARGET_METHOD)]
class TransitionsFor
{
    public function __construct(
        public \BackedEnum&State $state,
    ) {}
}
