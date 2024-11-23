<?php

namespace Rest\Http\Request;

use Rest\Authenticators\Authenticator;
use Rest\Http\Method;
use Rest\Http\Parameters\ImmutableParameterCollection;
use Rest\Http\Segments\ImmutableSegmentCollection;

final readonly class RestRequest
{
    public function __construct(
        public string $resource,
        public Method $method = Method::GET,
        public ImmutableParameterCollection $queryParameters = new ImmutableParameterCollection(),
        public ImmutableParameterCollection $headers = new ImmutableParameterCollection(),
        public ImmutableSegmentCollection $segments = new ImmutableSegmentCollection(),
        public ?string $body = null,
        public ?Authenticator $authenticator = null,
    ) {}
}