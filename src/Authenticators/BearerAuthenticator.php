<?php

declare(strict_types=1);

namespace Rest\Authenticators;

use Rest\Http\Request\RestRequestBuilder;
use Rest\RestClientInterface;

final readonly class BearerAuthenticator implements Authenticator
{
    public function __construct(private string $token)
    {
    }

    public function authenticate(RestClientInterface $client, RestRequestBuilder &$request): void
    {
        $request->withHeader('Authorization', 'Bearer ' . $this->token);
    }
}
