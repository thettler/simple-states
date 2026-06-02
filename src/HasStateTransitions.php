<?php

declare(strict_types=1);

namespace Thettler\SimpleStates;

use ReflectionEnum;
use Thettler\SimpleStates\Attributes\CanTransitionTo;
use Thettler\SimpleStates\Attributes\CanTransitionToClass;
use Thettler\SimpleStates\Attributes\TransitionsFor;
use Thettler\SimpleStates\Exceptions\SimpleStateAttributeMissingException;
use Thettler\SimpleStates\Exceptions\SimpleStateNotAllowedTransitionException;


trait HasStateTransitions
{
    use HasBaseStateFunctions;

    /**
     * @template T of (\BackedEnum&State)
     * @param  T  $state
     * @return T
     * @throws SimpleStateAttributeMissingException
     */
    public function transitionTo(\BackedEnum&State $state): \BackedEnum&State
    {
        if (!$this->canTransitionTo($state)) {
            SimpleStateNotAllowedTransitionException::throw($this, $state);
        }

        return $state;
    }

    public function canTransitionTo(\BackedEnum&State $transitionTo, ...$params): bool
    {
        return in_array($transitionTo, $this->getTransitions(...$params), strict: true);
    }

    public function getTransitions(...$params): array
    {
        [$reflection, $case] = $this->getCaseReflection();

        $attributes = $case->getAttributes(CanTransitionTo::class);

        if (!empty($attributes)) {
            return $this->getTransitionsByAttribute($attributes);
        }

        $attributes = $case->getAttributes(CanTransitionToClass::class);

        if (!empty($attributes)) {
            $attribute = $attributes[0]->newInstance();

            return $this->getTransitionsByClass($attribute, $params);
        }

        try {
            $method = $reflection->getMethod("transitionsFor{$this->name}");

            return $this->invokeComputedMethod($method, $params);
        } catch (\ReflectionException) {
            return $this->getTransitionsByAttributeMarkedMethod($reflection, $params);
        }
    }

    /**
     * @param  \ReflectionAttribute[]  $attributes
     * @return mixed
     */
    protected function getTransitionsByAttribute(array $attributes): mixed
    {
        return array_reduce(
            $attributes,
            static fn(array $carry, \ReflectionAttribute $attribute) => [
                ...$carry,
                ...$attribute->newInstance()->states,
            ],
            [],
        );
    }

    protected function getTransitionsByClass(CanTransitionToClass $attribute, array $params): array
    {

        if (!class_exists($attribute->class)) {
            throw new \InvalidArgumentException("Invalid class name: {$attribute->class}");
        }

        if (!in_array(TransitionGuard::class, class_implements($attribute->class))) {
            throw new \InvalidArgumentException(
                "Transition class {$attribute->class} must implement TransitionGuard interface",
            );
        }

        return (new ($attribute->class))->transitions($this, ...$params);
    }

    /**
     * @return array
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

                if (!property_exists($this, 'value')) {
                    throw new \InvalidArgumentException('Trait '.static::class.' must be used on an BackedEnum');
                }

                if ($attribute->state->value !== $this->value) {
                    continue;
                }

                // @mago-expect analysis:mixed-return-statement
                return $this->invokeComputedMethod($method, $params);
            }
        }

        return [];
    }
}
