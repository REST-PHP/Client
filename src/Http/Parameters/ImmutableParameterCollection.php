<?php

declare(strict_types=1);

namespace Rest\Http\Parameters;

/**
 * @template TValue
 */
final class ImmutableParameterCollection
{
    /** @use ReadsParameters<TValue> */
    use ReadsParameters;
}
