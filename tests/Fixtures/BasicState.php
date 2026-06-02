<?php

declare(strict_types=1);

namespace Thettler\SimpleStates\Tests\Fixtures;

use Thettler\SimpleStates\Attributes\StateAttribute;
use Thettler\SimpleStates\Attributes\StateAttributeComputed;
use Thettler\SimpleStates\IsState;
use Thettler\SimpleStates\State;

enum BasicState: string implements State
{
    use IsState;

    #[StateAttribute('color', 'orange')]
    case Draft = 'draft';

    #[StateAttribute('color', 'blue')]
    case Published = 'published';

    #[StateAttribute('color', StateAttributeClass::class)]
    case Approved = 'approved';

    case Archived = 'archived';

    public function colorArchived(): string
    {
        return 'gray';
    }

    case Deleted = 'deleted';

    private function _someMethod(): string
    {
        return 'foo';
    }

    #[StateAttributeComputed('color', self::Deleted)]
    private function _getDeletedColor(): string
    {
        return 'red';
    }
}
