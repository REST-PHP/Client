<?php

declare(strict_types=1);

namespace Rest\Http;

final readonly class Status
{
    public function __construct(
        public StatusCode $code,
        public string $message,
    ) {
    }
}
