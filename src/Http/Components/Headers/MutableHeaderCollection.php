<?php

declare(strict_types=1);

namespace Rest\Http\Components\Headers;

final class MutableHeaderCollection
{
    use ReadsHeaders;
    use WritesHeaders;

    public function toImmutable(): ImmutableHeaderCollection
    {
        return new ImmutableHeaderCollection($this->all());
    }
}
