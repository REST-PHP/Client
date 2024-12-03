<?php

declare(strict_types=1);

namespace Rest\Http\Components\QueryParameters;

trait WritesQueryParameters
{
    /**
     * @var array<string,scalar[]>
     */
    private array $queryParameters = [];

    public function add(string $name, bool|float|int|string $value): self
    {
        if (! isset($this->queryParameters[$name])) {
            $this->queryParameters[$name] = [];
        }

        $this->queryParameters[$name][] = $value;

        return $this;
    }

    /**
     * @param array<scalar>|scalar $value
     */
    public function set(string $name, array|bool|float|int|string $value): self
    {
        if (! is_array($value)) {
            $value = [$value];
        }

        $this->queryParameters[$name] = $value;

        return $this;
    }

    /**
     * @param array<string,scalar|scalar[]> $queryParameters
     */
    public function merge(array $queryParameters): self
    {
        foreach ($queryParameters as $name => $value) {
            if (! is_array($value)) {
                $value = [$value];
            }

            foreach ($value as $item) {
                $this->add($name, $item);
            }
        }

        return $this;
    }

    /**
     * @param array<string,scalar|scalar[]> $queryParameters
     */
    public function replace(array $queryParameters): self
    {
        $this->queryParameters = [];

        $this->merge($queryParameters);

        return $this;
    }

    abstract public function __construct(array $queryParameters = []);
}
