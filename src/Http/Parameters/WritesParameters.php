<?php

declare(strict_types=1);

namespace Rest\Http\Parameters;

/**
 * @template TValue
 */
trait WritesParameters
{
    /**
     * @param TValue $value
     * @return self<TValue>
     */
    public function add(string $key, $value): self
    {
        if (! isset($this->parameters[$key])) {
            $this->parameters[$key] = [];
        }

        $this->parameters[$key][] = $value;

        return $this;
    }

    /**
     * @param TValue $value
     * @return self<TValue>
     */
    public function set(string $key, $value): self
    {
        $this->parameters[$key] = [$value];

        return $this;
    }

    /**
     * @return self<TValue>
     */
    public function unset(string $key): self
    {
        unset($this->parameters[$key]);

        return $this;
    }

    /**
     * @param array<string,TValue|TValue[]> $parameters
     * @return self<TValue>
     */
    public function merge(array $parameters): self
    {
        foreach ($parameters as $key => $value) {
            if (! is_array($value)) {
                $value = [$value];
            }

            foreach ($value as $item) {
                $this->add($key, $item);
            }
        }

        return $this;
    }

    /**
     * @param array<string,TValue|TValue[]> $parameters
     * @return self<TValue>
     */
    public function replace(array $parameters): self
    {
        $this->parameters = [];

        $this->merge($parameters);

        return $this;
    }
}
