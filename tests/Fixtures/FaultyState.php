<?php

declare(strict_types=1);

namespace Thettler\SimpleStates\Tests\Fixtures;

use Thettler\SimpleStates\Attributes\StateAttribute;
use Thettler\SimpleStates\Attributes\StateAttributeComputed;
use Thettler\SimpleStates\IsState;
use Thettler\SimpleStates\State;

enum FaultyState: string implements State
{
    use IsState;

    #[StateAttribute('color', 'green')]
    case Approved = 'approved';

    case Archived = 'archived';
    case Deleted = 'deleted';

    #[StateAttributeComputed('info', self::Deleted)]
    private function _deleteInfo(): string
    {
        return 'deleted';
    }
}
