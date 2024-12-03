<?php

declare(strict_types=1);

namespace Rest\Http\Components\Headers;

trait WritesHeaders
{
    /**
     * @var array<string,scalar[]>
     */
    private array $headers = [];

    /**
     * Adds a value to the header field, leaving any existing entries.
     */
    public function add(string $name, bool|float|int|string $value): self
    {
        $name = $this->normalizeName($name);

        if (! isset($this->headers[$name])) {
            $this->headers[$name] = [];
        }

        if (! in_array($value, $this->headers[$name])) {
            $this->headers[$name][] = $value;
        }

        return $this;
    }

    /**
     * As opposed to the "add" method, which adds another entry to the
     * header field, this method replaces any existing entries as well.
     */
    public function set(string $name, bool|float|int|string $value): self
    {
        $name = $this->normalizeName($name);

        $this->headers[$name] = [$value];

        return $this;
    }

    public function unset(string $name): self
    {
        $name = $this->normalizeName($name);

        unset($this->headers[$name]);

        return $this;
    }

    /**
     * @param array<string,scalar|scalar[]> $headers
     */
    public function merge(array $headers): self
    {
        foreach ($headers as $name => $value) {
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
     * @param array<string,scalar|scalar[]> $headers
     */
    public function replace(array $headers): self
    {
        $this->headers = [];

        $this->merge($headers);

        return $this;
    }

    /**
     * @param array<string,scalar[]> $headers
     */
    abstract public function __construct(array $headers = []);

    abstract private function normalizeName(string $name): string;
}
