<?php

declare(strict_types=1);

it('throws error if only UnitEnum', function () {
    \Thettler\SimpleStates\Tests\Fixtures\UnitEnum::A->test();
})->throws(\Thettler\SimpleStates\Exceptions\SimpleStateMustBeUsedOnBackedEnumException::class);

it('throws error if used on non BackedEnum getCaseReflection', function () {
    $class = new class {
        use \Thettler\SimpleStates\HasBaseStateFunctions;

        public function __construct()
        {
            $this->getCaseReflection();
        }
    };
})->throws(\Thettler\SimpleStates\Exceptions\SimpleStateMustBeUsedOnBackedEnumException::class);

it('throws error if used on non getName', function () {
    $class = new class {
        use \Thettler\SimpleStates\HasBaseStateFunctions;

        public function __construct()
        {
            $this->getName();
        }
    };
})->throws(\Thettler\SimpleStates\Exceptions\SimpleStateMustBeUsedOnBackedEnumException::class);

it('throws error if used on non getValue', function () {
    $class = new class {
        use \Thettler\SimpleStates\HasBaseStateFunctions;

        public function __construct()
        {
            $this->getValue();
        }
    };
})->throws(\Thettler\SimpleStates\Exceptions\SimpleStateMustBeUsedOnBackedEnumException::class);
