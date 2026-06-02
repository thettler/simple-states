<?php

declare(strict_types=1);

namespace Thettler\SimpleStates\Tests\Fixtures;

class StateAttributeClassWithParam
{
    public function __construct(
        public WithParamsState $state,
        public string $color,
    ) {}

    public function __invoke(): string
    {
        return $this->color;
    }
}
