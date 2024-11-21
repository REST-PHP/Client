<?php

namespace Rest\Http\Parameters;

trait ReadsParameters
{
    /**
     * @param array<string,string[]> $parameters
     */
    public function __construct(private array $parameters = [])
    {}

    public function get(string $key): ?array
    {
        return $this->parameters[$key] ?? null;
    }

    public function first(string $key): ?string
    {
        return $this->parameters[$key][0] ?? null;
    }

    /**
     * @return array<string,string[]>
     */
    public function all(): array
    {
        return $this->parameters;
    }
}