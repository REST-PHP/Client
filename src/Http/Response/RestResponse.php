<?php

declare(strict_types=1);

namespace Rest\Http\Response;

use Rest\Http\Components\Headers\ImmutableHeaderCollection;
use Rest\Http\Request\RestRequest;
use Rest\Http\StatusCode;

final readonly class RestResponse
{
    public function __construct(
        public RestRequest $request,
        public StatusCode $statusCode,
        public string $reasonPhrase = '',
        public ImmutableHeaderCollection $headers = new ImmutableHeaderCollection(),
        public ?string $body = null,
        public ?string $contentType = null,
    ) {
    }
}
