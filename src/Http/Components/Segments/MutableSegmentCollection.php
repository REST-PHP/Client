<?php

declare(strict_types=1);

namespace Rest\Http\Components\Segments;

final class MutableSegmentCollection
{
    use ReadsSegments;
    use WritesSegments;

    public function toImmutable(): ImmutableSegmentCollection
    {
        return new ImmutableSegmentCollection($this->all());
    }
}
