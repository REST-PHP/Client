<?php

namespace Rest\Http\Authenticators;

use Rest\Http\Request\RestRequest;
use Rest\Http\RestClientInterface;

final readonly class BearerAuthenticator implements Authenticator
{
    public function __construct(private string $token)
    {}

    public function authenticate(RestClientInterface $client, RestRequest $request): RestRequest
    {
        return $request->addHeader('Authorization', 'Bearer ' . $this->token);
    }
}