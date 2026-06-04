<?php

declare(strict_types=1);

namespace Thettler\SimpleStates\Attributes;

#[\Attribute(\Attribute::TARGET_CLASS_CONSTANT | \Attribute::IS_REPEATABLE)]
class StateAttribute
{
    public function __construct(
        public string $attribute,
        public mixed $value,
    ) {}
}
