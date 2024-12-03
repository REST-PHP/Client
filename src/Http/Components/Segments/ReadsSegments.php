<?php

declare(strict_types=1);

namespace Rest\Http\Components\Segments;

use RuntimeException;

trait ReadsSegments
{
    /**
     * @param array<string,string|int> $segments
     */
    final public function __construct(private array $segments = [])
    {
    }

    public function get(string $segment): null|string|int
    {
        return $this->segments[$segment] ?? null;
    }

    /**
     * @return array<string,string|int>
     */
    public function all(): array
    {
        return $this->segments;
    }

    public function apply(string $url): string
    {
        $originalUrl = $url;

        foreach ($this->segments as $segment => $value) {
            $url = preg_replace('/{\s*'. $segment .'\s*}/', strval($value), $url);

            if (is_null($url)) {
                throw new RuntimeException('There was an error replacing the segment "'. $segment .'" in the URL "'. $originalUrl .'."');
            }
        }

        return $url;
    }
}
