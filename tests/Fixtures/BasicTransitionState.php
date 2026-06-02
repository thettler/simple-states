<?php

declare(strict_types=1);

namespace Thettler\SimpleStates\Tests\Fixtures;

use Thettler\SimpleStates\Attributes\CanTransitionTo;
use Thettler\SimpleStates\Attributes\CanTransitionToClass;
use Thettler\SimpleStates\Attributes\TransitionsFor;
use Thettler\SimpleStates\IsState;
use Thettler\SimpleStates\State;

enum BasicTransitionState: string implements State
{
    use IsState;

    #[CanTransitionTo(self::Approved, self::Archived)]
    case Draft = 'draft';

    case Approved = 'approved';

    case Published = 'published';

    #[CanTransitionToClass(StateTransitionClass::class)]
    case Archived = 'archived';

    case NoMethod = 'no_method';

    private function transitionsForApproved(bool $_flag1 = false, bool $flag2 = false): array
    {
        if ($flag2) {
            return [
                self::Draft,
            ];
        }

        return [
            self::Published,
            self::Archived,
        ];
    }

    #[TransitionsFor(self::Published)]
    private function getTransitionsForPublish(bool $flag = false): array
    {
        if ($flag) {
            return [
                self::Draft,
            ];
        }

        return [
            self::Archived,
        ];
    }
}
