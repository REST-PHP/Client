<?php

namespace Rest\Http\Response;

use Rest\Http\Parameters\ImmutableParameterCollection;
use Rest\Http\Request\RestRequest;
use Rest\Http\StatusCode;

final readonly class RestResponse
{
    public function __construct(
        public RestRequest $request,
        public StatusCode $statusCode,
        public string $reasonPhrase = '',
        public ImmutableParameterCollection $headers = new ImmutableParameterCollection(),
        public ?string $body = null,
        public ?string $contentType = null,
    ) {}
}