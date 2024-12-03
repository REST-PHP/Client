<?php

declare(strict_types=1);

namespace Rest\Http\Components\Headers;

trait ReadsHeaders
{
    /**
     * @var array<string,scalar[]>
     */
    private array $headers = [];

    /**
     * @param array<string,scalar[]> $headers
     */
    public function __construct(array $headers = [])
    {
        $this->headers = $headers;
    }

    /**
     * @return array<scalar>
     */
    public function get(string $name): array
    {
        $name = $this->normalizeName($name);

        return $this->headers[$name] ?? [];
    }

    public function first(string $name): null|bool|float|int|string
    {
        $name = $this->normalizeName($name);

        return $this->headers[$name][0] ?? null;
    }

    /**
     * @return array<string,scalar[]>
     */
    public function all(): array
    {
        return $this->headers;
    }

    private function normalizeName(string $name): string
    {
        return strtolower($name);
    }
}
