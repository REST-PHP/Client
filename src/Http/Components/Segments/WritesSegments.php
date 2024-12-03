<?php

declare(strict_types=1);

namespace Rest\Http\Components\Segments;

trait WritesSegments
{
    public function set(string $segment, string|int $value): self
    {
        $this->segments[$segment] = $value;

        return $this;
    }
}
