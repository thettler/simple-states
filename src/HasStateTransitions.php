<?php

declare(strict_types=1);

namespace Thettler\SimpleStates;

use ReflectionEnum;
use Thettler\SimpleStates\Attributes\CanTransitionTo;
use Thettler\SimpleStates\Attributes\CanTransitionToClass;
use Thettler\SimpleStates\Attributes\TransitionsFor;
use Thettler\SimpleStates\Exceptions\SimpleStateMustBeUsedOnBackedEnumException;
use Thettler\SimpleStates\Exceptions\SimpleStateNotAllowedTransitionException;

/**
 * @phpstan-require-implements State
 */
trait HasStateTransitions
{
    use HasBaseStateFunctions;

    /**
     * @template T of (\BackedEnum&State)
     * @param  T  $state
     * @return T
     * @throws SimpleStateNotAllowedTransitionException
     * @throws SimpleStateMustBeUsedOnBackedEnumException
     * @throws \ReflectionException
     */
    public function transitionTo(\BackedEnum&State $state, ...$params): \BackedEnum&State
    {
        if (! $this->canTransitionTo($state, ...$params)) {
            SimpleStateNotAllowedTransitionException::throw($this, $state);
        }

        return $state;
    }

    /**
     * @throws SimpleStateMustBeUsedOnBackedEnumException
     * @throws \ReflectionException
     */
    public function canTransitionTo(\BackedEnum&State $transitionTo, ...$params): bool
    {
        return in_array($transitionTo, $this->getTransitions(...$params), strict: true);
    }

    /**
     * @throws SimpleStateMustBeUsedOnBackedEnumException
     * @throws \ReflectionException
     */
    public function getTransitions(...$params): array
    {
        [$reflection, $case] = $this->getCaseReflection();

        $attributes = $case->getAttributes(CanTransitionTo::class);

        if (! empty($attributes)) {
            return $this->getTransitionsByAttribute($attributes);
        }

        $attributes = $case->getAttributes(CanTransitionToClass::class);

        if (! empty($attributes)) {
            $attribute = $attributes[0]->newInstance();

            return $this->getTransitionsByClass($attribute, $params);
        }

        try {
            $method = $reflection->getMethod("transitionsFor{$this->getName()}");

            // @mago-expect analysis:mixed-return-statement
            return $this->invokeComputedMethod($method, $params);
        } catch (\ReflectionException) {
            return $this->getTransitionsByAttributeMarkedMethod($reflection, $params);
        }
    }

    /**
     * @param  \ReflectionAttribute<CanTransitionTo>[]  $attributes
     */
    protected function getTransitionsByAttribute(array $attributes): array
    {
        return array_reduce(
            $attributes,
            /** @param  \ReflectionAttribute<CanTransitionTo>  $attribute */
            static fn (array $carry, \ReflectionAttribute $attribute) => [
                ...$carry,
                ...$attribute->newInstance()->states,
            ],
            [],
        );
    }

    protected function getTransitionsByClass(CanTransitionToClass $attribute, array $params): array
    {
        if (! class_exists($attribute->class)) {
            throw new \InvalidArgumentException("Invalid class name: {$attribute->class}");
        }

        // @mago-expect analysis:unknown-class-instantiation
        $class = new $attribute->class;

        if (! method_exists($class, '__invoke')) {
            throw new \InvalidArgumentException(
                "Transition class {$attribute->class} must have __invoke method.",
            );
        }

        // @mago-expect analysis:mixed-return-statement
        return $class->__invoke($this, ...$params);
    }

    /**
     * @return array
     * @throws \ReflectionException
     * @throws SimpleStateMustBeUsedOnBackedEnumException
     */
    protected function getTransitionsByAttributeMarkedMethod(ReflectionEnum $reflection, array $params): array
    {
        $methods = $reflection->getMethods(\ReflectionMethod::IS_PRIVATE);

        foreach ($methods as $method) {
            $attributes = $method->getAttributes(TransitionsFor::class);

            if (empty($attributes)) {
                continue;
            }

            /** @var \ReflectionAttribute<TransitionsFor>[] $attributes */
            foreach ($attributes as $attribute) {
                $attribute = $attribute->newInstance();

                if ($attribute->state->value !== $this->getValue()) {
                    continue;
                }

                // @mago-expect analysis:mixed-return-statement
                return $this->invokeComputedMethod($method, $params);
            }
        }

        return [];
    }
}
