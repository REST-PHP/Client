<?php

declare(strict_types=1);

namespace Rest\Authenticators;

use Rest\Http\Request\RestRequestBuilder;
use Rest\RestClientInterface;

interface Authenticator
{
    public function authenticate(RestClientInterface $client, RestRequestBuilder &$request): void;
}
