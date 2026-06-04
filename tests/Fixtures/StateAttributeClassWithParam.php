<?php

declare(strict_types=1);

namespace Thettler\SimpleStates\Tests\Fixtures;

class StateAttributeClassWithParam
{
    public function __invoke(
        WithParamsState $state,
        string $color,
    ): string {
        return $color;
    }
}
