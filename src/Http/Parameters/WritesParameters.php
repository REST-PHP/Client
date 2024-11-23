<?php

namespace Rest\Http\Parameters;

trait WritesParameters
{
    public function add(string $key, string $value): self
    {
        if (! isset($this->parameters[$key])) {
            $this->parameters[$key] = [];
        }

        $this->parameters[$key][] = $value;

        return $this;
    }

    public function set(string $key, string $value): self
    {
        $this->parameters[$key] = [$value];

        return $this;
    }

    public function unset(string $key): self
    {
        unset($this->parameters[$key]);

        return $this;
    }

    /**
     * @param array<string,string|string[]> $parameters
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
     * @param array<string,string|string[]> $parameters
     */
    public function replace(array $parameters): self
    {
        $this->parameters = [];

        $this->merge($parameters);

        return $this;
    }
}