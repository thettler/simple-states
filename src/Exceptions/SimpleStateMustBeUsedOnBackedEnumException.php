<?php

declare(strict_types=1);

namespace Thettler\SimpleStates\Exceptions;

class SimpleStateMustBeUsedOnBackedEnumException extends \Exception
{
    /**
     * @throws SimpleStateMustBeUsedOnBackedEnumException
     */
    public static function throw(string $class): never
    {
        throw new self("The IsState Trait must be used on Backed Enum. Used on {$class}");
    }
}
