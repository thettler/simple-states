<?php

declare(strict_types=1);

namespace Thettler\SimpleStates;

trait IsState
{
    use HasStateTransitions;
    use HasStateAttributes;
}
