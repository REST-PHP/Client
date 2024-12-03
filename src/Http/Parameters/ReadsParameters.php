<?php

declare(strict_types=1);

namespace Rest\Http\Parameters;

/**
 * @template TValue
 */
trait ReadsParameters
{
    /**
     * @param array<string,TValue[]> $parameters
     */
    final public function __construct(private array $parameters = [])
    {
    }

    /**
     * @return array<TValue>|null
     */
    public function get(string $key): ?array
    {
        return $this->parameters[$key] ?? null;
    }

    /**
     * @return TValue|null
     */
    public function first(string $key)
    {
        return $this->parameters[$key][0] ?? null;
    }

    /**
     * @return array<string,TValue[]>
     */
    public function all(): array
    {
        return $this->parameters;
    }
}
