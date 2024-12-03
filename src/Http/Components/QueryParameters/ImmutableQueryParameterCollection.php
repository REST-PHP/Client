<?php

declare(strict_types=1);

namespace Rest\Http\Components\QueryParameters;

final class ImmutableQueryParameterCollection
{
    use ReadsQueryParameters;
    use WritesQueryParameters;
}
