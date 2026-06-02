<?php

declare(strict_types=1);

namespace Thettler\SimpleStates;

trait HasBaseStateFunctions
{
    /**
     * @param  array  $params
     * @param  \ReflectionMethod  $method
     * @return mixed
     */
    protected function invokeComputedMethod(\ReflectionMethod $method, array $params): mixed
    {
        return ! empty($params)
            ? $method->invokeArgs($this, $params)
            : $method->invoke($this);
    }

    /**
     * @return array [ReflectionEnum, \ReflectionEnumCase]
     * @throws \ReflectionException
     */
    protected function getCaseReflection(): array
    {
        $reflection = new \ReflectionEnum($this);

        $case = $reflection->getCase($this->name);

        return [$reflection, $case];
    }
}
