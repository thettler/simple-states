<?php

declare(strict_types=1);

namespace Thettler\SimpleStates;

use Thettler\SimpleStates\Attributes\StateAttribute;
use Thettler\SimpleStates\Attributes\StateAttributeComputed;
use Thettler\SimpleStates\Exceptions\SimpleStateAttributeClassHasNoInvokeMethod;
use Thettler\SimpleStates\Exceptions\SimpleStateAttributeMissingException;
use Thettler\SimpleStates\Exceptions\SimpleStateMustBeUsedOnBackedEnumException;

/**
 * @phpstan-require-implements State
 */
trait HasStateAttributes
{
    use HasBaseStateFunctions;

    /**
     * @throws SimpleStateAttributeMissingException
     * @throws \ReflectionException
     * @throws SimpleStateAttributeClassHasNoInvokeMethod
     * @throws SimpleStateMustBeUsedOnBackedEnumException
     */
    public function getStateAttribute(string $attributeName, ...$params): mixed
    {
        [$reflection, $case] = $this->getCaseReflection();

        $attributes = $case->getAttributes(StateAttribute::class);

        if (empty($attributes)) {
            return $this->getStateAttributeByMethod($reflection, $attributeName, ...$params);
        }

        return $this->getStateAttributeByAttribute($attributes, $attributeName, ...$params);
    }

    /**
     * @param  \ReflectionAttribute<StateAttribute>[]  $attributes
     * @param  string  $attributeName
     * @return mixed
     * @throws SimpleStateAttributeMissingException
     * @throws SimpleStateAttributeClassHasNoInvokeMethod
     */
    protected function getStateAttributeByAttribute(array $attributes, string $attributeName, ...$params): mixed
    {
        foreach ($attributes as $attribute) {
            $attribute = $attribute->newInstance();

            if ($attribute->attribute !== $attributeName) {
                continue;
            }

            // @mago-expect analysis:mixed-assignment
            $value = $attribute->value;

            if (! is_string($value)) {
                return $value;
            }

            if (! class_exists($value)) {
                return $value;
            }

            // @mago-expect analysis:unknown-class-instantiation
            $class = new $value;

            if (! method_exists($class, '__invoke')) {
                SimpleStateAttributeClassHasNoInvokeMethod::throw($this, $attributeName);
            }

            return $class->__invoke($this, ...$params);
        }

        SimpleStateAttributeMissingException::throw($this, $attributeName);
    }

    /**
     * @throws SimpleStateAttributeMissingException
     * @throws SimpleStateMustBeUsedOnBackedEnumException
     * @throws \ReflectionException
     */
    protected function getStateAttributeByMethod(\ReflectionEnum $reflection, string $attributeName, ...$params): mixed
    {
        try {
            return $this->getStateAttributeByMethodConvention($reflection, $attributeName, ...$params);
        } catch (\ReflectionException) {
            return $this->getStateAttributeByMethodAttribute($reflection, $attributeName, ...$params);
        }
    }

    /**
     * @throws \ReflectionException
     * @throws SimpleStateMustBeUsedOnBackedEnumException
     */
    protected function getStateAttributeByMethodConvention(
        \ReflectionEnum $reflection,
        string $attributeName,
        ...$params,
    ): mixed {
        $method = $reflection->getMethod("{$attributeName}{$this->getName()}");

        return $this->invokeComputedMethod($method, $params);
    }

    /**
     * @throws SimpleStateAttributeMissingException
     * @throws \ReflectionException
     * @throws SimpleStateMustBeUsedOnBackedEnumException
     */
    protected function getStateAttributeByMethodAttribute(
        \ReflectionEnum $reflection,
        string $attributeName,
        ...$params,
    ): mixed {
        $methods = $reflection->getMethods(\ReflectionMethod::IS_PRIVATE);

        foreach ($methods as $method) {
            $attributes = $method->getAttributes(StateAttributeComputed::class);

            if (empty($attributes)) {
                continue;
            }

            /** @var \ReflectionAttribute<StateAttributeComputed>[] $attributes */
            foreach ($attributes as $attribute) {
                $attribute = $attribute->newInstance();

                if (
                    $attribute->attribute !== $attributeName
                    || $attribute->state->value !== $this->getValue()
                ) {
                    continue;
                }

                return $this->invokeComputedMethod($method, $params);
            }
        }

        SimpleStateAttributeMissingException::throw($this, $attributeName);
    }
}
