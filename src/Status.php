<?php

declare(strict_types=1);

namespace Rest\Http;

final readonly class Status
{
    public function __construct(
        public int $code,
        public string $message,
    ) {
    }
}
