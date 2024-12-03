<?php

declare(strict_types=1);

namespace Rest\Http\Parameters;

/**
 * @template TValue
 */
final class MutableParameterCollection
{
    /** @use ReadsParameters<TValue> */
    use ReadsParameters;

    /** @use WritesParameters<TValue> */
    use WritesParameters;
}
