<?php

declare(strict_types=1);

namespace Thettler\SimpleStates;

use Thettler\SimpleStates\Attributes\StateAttribute;
use Thettler\SimpleStates\Attributes\StateAttributeComputed;
use Thettler\SimpleStates\Exceptions\SimpleStateAttributeMissingException;

trait HasStateAttributes
{
    use HasBaseStateFunctions;

    /**
     * @throws SimpleStateAttributeMissingException
     * @throws \ReflectionException
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
     */
    protected function getStateAttributeByAttribute(array $attributes, string $attributeName, ...$params): mixed
    {
        foreach ($attributes as $attribute) {
            $attribute = $attribute->newInstance();

            if ($attribute->attribute !== $attributeName) {
                continue;
            }

            $value = $attribute->value;

            return class_exists($value) ? (new $value($this, ...$params))() : $value;
        }

        SimpleStateAttributeMissingException::throw($this, $attributeName);
    }

    /**
     * @throws SimpleStateAttributeMissingException
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
     */
    protected function getStateAttributeByMethodConvention(
        \ReflectionEnum $reflection,
        string $attributeName,
        ...$params,
    ): mixed {
        $method = $reflection->getMethod("{$attributeName}{$this->name}");

        return $this->invokeComputedMethod($method, $params);
    }

    /**
     * @throws SimpleStateAttributeMissingException
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
                if (!property_exists($this, 'value')) {
                    throw new \InvalidArgumentException('Trait '.static::class.' must be used on an BackedEnum');
                }

                if (
                    $attribute->attribute !== $attributeName
                    || $attribute->state->value !== $this->value
                ) {
                    continue;
                }

                return $this->invokeComputedMethod($method, $params);
            }
        }

        SimpleStateAttributeMissingException::throw($this, $attributeName);
    }
}
