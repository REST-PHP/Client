<?php

namespace Rest\Http\Segments;

trait WritesSegments
{
    public function set(string $segment, string|int $value): self
    {
        $this->segments[$segment] = $value;

        return $this;
    }
}