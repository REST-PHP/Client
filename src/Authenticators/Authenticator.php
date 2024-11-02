<?php

declare(strict_types=1);

namespace Rest\Http\Authenticators;

use Rest\Http\Request\RestRequest;
use Rest\Http\RestClientInterface;

interface Authenticator
{
    public function authenticate(RestClientInterface $client, RestRequest $request): RestRequest;
}
