<?php

namespace Rest\Tests\Authenticators;

use PHPUnit\Framework\TestCase;
use Rest\Authenticators\BearerAuthenticator;
use Rest\Http\Request\RestRequestBuilder;
use Rest\RestClient;

class BearerAuthenticatorTest extends TestCase
{
    public function test_authorization_header_is_added()
    {
        $client = new RestClient();
        $request = new RestRequestBuilder('users');

        $authenticator = new BearerAuthenticator('test-token');
        $authenticator->authenticate($client, $request);

        $this->assertSame('Bearer test-token', $request->headers->first('Authorization'));
    }
}