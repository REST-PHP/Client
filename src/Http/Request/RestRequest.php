<?php

declare(strict_types=1);

namespace Rest\Http\Request;

use Rest\Authenticators\Authenticator;
use Rest\Http\Components\Headers\ImmutableHeaderCollection;
use Rest\Http\Components\QueryParameters\ImmutableQueryParameterCollection;
use Rest\Http\Components\Segments\ImmutableSegmentCollection;
use Rest\Http\Method;

final readonly class RestRequest
{
    public function __construct(
        public string $resource,
        public Method $method = Method::GET,
        public ImmutableQueryParameterCollection $queryParameters = new ImmutableQueryParameterCollection(),
        public ImmutableHeaderCollection $headers = new ImmutableHeaderCollection(),
        public ImmutableSegmentCollection $segments = new ImmutableSegmentCollection(),
        public ?string $body = null,
        public ?Authenticator $authenticator = null,
    ) {
    }
}
