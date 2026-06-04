<?php

declare(strict_types=1);

namespace Thettler\SimpleStates;

use BackedEnum;
use Thettler\SimpleStates\Exceptions\SimpleStateMustBeUsedOnBackedEnumException;

trait HasBaseStateFunctions
{
    /**
     * @param  array  $params
     * @param  \ReflectionMethod  $method
     * @return mixed
     * @throws \ReflectionException
     */
    protected function invokeComputedMethod(\ReflectionMethod $method, array $params): mixed
    {
        return ! empty($params)
            ? $method->invokeArgs($this, $params)
            : $method->invoke($this);
    }

    /**
     * @return array{\ReflectionEnum, \ReflectionEnumBackedCase}
     * @throws SimpleStateMustBeUsedOnBackedEnumException
     * @throws \ReflectionException
     */
    protected function getCaseReflection(): array
    {
        if (! $this instanceof BackedEnum) {
            SimpleStateMustBeUsedOnBackedEnumException::throw($this::class);
        }

        $reflection = new \ReflectionEnum($this);

        /** @var \ReflectionEnumBackedCase $case */
        $case = $reflection->getCase($this->getName());

        return [$reflection, $case];
    }

    /**
     * @throws SimpleStateMustBeUsedOnBackedEnumException
     */
    public function getName(): string
    {
        if (! $this instanceof \BackedEnum) {
            SimpleStateMustBeUsedOnBackedEnumException::throw($this::class);
        }

        return $this->name;
    }

    /**
     * @throws SimpleStateMustBeUsedOnBackedEnumException
     */
    protected function getValue(): string|int
    {
        if (! $this instanceof \BackedEnum) {
            SimpleStateMustBeUsedOnBackedEnumException::throw($this::class);
        }

        return $this->value;
    }
}
