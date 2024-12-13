<?php

declare(strict_types=1);

namespace Rest\Http\Components\QueryParameters;

trait ReadsQueryParameters
{
    /**
     * @var array<string,scalar[]>
     */
    private array $queryParameters = [];

    /**
     * @param array<string,scalar[]> $queryParameters
     */
    public function __construct(array $queryParameters = [])
    {
        $this->queryParameters = $queryParameters;
    }

    /**
     * Returns the query parameter. If the parameter is
     * repeated multiple times all values are returned.
     *
     * @return array<scalar>|scalar|null
     */
    public function get(string $name): null|array|bool|float|int|string
    {
        $value = $this->queryParameters[$name] ?? null;

        if ($value === null) {
            return null;
        }

        return count($value) > 1 ? $value : $value[0];
    }

    public function first(string $name): null|bool|float|int|string
    {
        return $this->queryParameters[$name][0] ?? null;
    }

    /**
     * @return array<string,scalar[]>
     */
    public function all(): array
    {
        return $this->queryParameters;
    }
}
