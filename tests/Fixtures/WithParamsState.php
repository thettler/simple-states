<?php

declare(strict_types=1);

namespace Thettler\SimpleStates\Tests\Fixtures;

use Thettler\SimpleStates\Attributes\StateAttribute;
use Thettler\SimpleStates\Attributes\StateAttributeComputed;
use Thettler\SimpleStates\IsState;
use Thettler\SimpleStates\State;

enum WithParamsState: string implements State
{
    use IsState;

    #[StateAttribute('color', StateAttributeClassWithParam::class)]
    case Approved = 'approved';

    case Archived = 'archived';

    public function colorArchived(string $color): string
    {
        return $color;
    }

    case Deleted = 'deleted';

    #[StateAttributeComputed('color', self::Deleted)]
    private function _getDeletedColor(string $color): string
    {
        return $color;
    }
}
