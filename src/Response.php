<?php

declare(strict_types=1);

namespace Rest\Http;

final readonly class Response
{
    /**
     * @param string|null $contentType
     * @param string|null $content
     * @param Status $status
     * @param array<array<string>> $headers
     */
    public function __construct(
        public ?string $contentType,
        public ?string $content,
        public Status $status,
        public array $headers = [],
    ) {
    }
}
