<?php

declare(strict_types=1);

namespace Rest\Http\Components\QueryParameters;

final class MutableQueryParameterCollection
{
    use ReadsQueryParameters;
    use WritesQueryParameters;

    public function toImmutable(): ImmutableQueryParameterCollection
    {
        return new ImmutableQueryParameterCollection($this->all());
    }
}
