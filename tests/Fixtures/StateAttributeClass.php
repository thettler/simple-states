<?php

declare(strict_types=1);

namespace Thettler\SimpleStates\Tests\Fixtures;

class StateAttributeClass
{
    public function __construct(
        public BasicState $state,
    ) {}

    public function __invoke(): string
    {
        return 'green';
    }
}
