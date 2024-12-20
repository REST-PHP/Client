<?php

declare(strict_types=1);

namespace Rest\Tests\Http\Request;

use PHPUnit\Framework\TestCase;
use Rest\Authenticators\BearerAuthenticator;
use Rest\Http\Components\Headers\ImmutableHeaderCollection;
use Rest\Http\Components\QueryParameters\ImmutableQueryParameterCollection;
use Rest\Http\Components\Segments\ImmutableSegmentCollection;
use Rest\Http\Method;
use Rest\Http\Request\RestRequest;
use Rest\Http\Request\RestRequestBuilder;

/**
 * @internal
 */
final class RestRequestBuilderTest extends TestCase
{
    public function test_making_a_request()
    {
        $request = new RestRequestBuilder('users')->make();

        $this->assertInstanceOf(RestRequest::class, $request);
        $this->assertSame('users', $request->resource);
        $this->assertSame(Method::GET, $request->method);
    }

    public function test_making_a_request_with_a_method()
    {
        $request = new RestRequestBuilder('users')
            ->withMethod(Method::PUT)
            ->make();

        $this->assertSame(Method::PUT, $request->method);
    }

    public function test_making_a_request_with_an_authenticator()
    {
        $authenticator = new BearerAuthenticator('test-token');
        $request = new RestRequestBuilder('users')
            ->withAuthenticator($authenticator)
            ->make();

        $this->assertSame($authenticator, $request->authenticator);
    }

    public function test_making_a_request_with_a_body()
    {
        $request = new RestRequestBuilder('users')
            ->withBody('Hello World')
            ->make();

        $this->assertSame('Hello World', $request->body);
    }

    public function test_making_a_request_with_an_added_header()
    {
        $request = new RestRequestBuilder('users')
            ->withHeader('Content-Type', 'application/json')
            ->make();

        $this->assertSame(['application/json'], $request->headers->get('Content-Type'));
    }

    public function test_making_a_request_with_multiple_headers()
    {
        $request = new RestRequestBuilder('users')
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => ['application/xml', 'application/json'],
            ])
            ->make();

        $this->assertSame(['application/json'], $request->headers->get('Content-Type'));
        $this->assertSame(['application/xml', 'application/json'], $request->headers->get('Accept'));
    }

    public function test_making_a_request_replacing_a_header()
    {
        $request = new RestRequestBuilder('users')
            ->withHeader('Content-Type', 'application/json')
            ->replaceHeader('Content-Type', 'text/plain')
            ->make();

        $this->assertSame(['text/plain'], $request->headers->get('Content-Type'));
    }

    public function test_making_a_request_replacing_all_headers()
    {
        $request = new RestRequestBuilder('users')
            ->withHeader('Content-Type', 'application/json')
            ->replaceHeaders([
                'Content-Type' => 'application/xml',
                'Accept' => ['application/xml', 'application/json'],
            ])
            ->make();

        $this->assertSame(['application/xml'], $request->headers->get('Content-Type'));
        $this->assertSame(['application/xml', 'application/json'], $request->headers->get('Accept'));
    }

    public function test_making_a_request_with_a_query_parameter()
    {
        $request = new RestRequestBuilder('users')
            ->withQueryParameter('search', 'my-query')
            ->make();

        $this->assertSame('my-query', $request->queryParameters->get('search'));
    }

    public function test_making_a_request_with_query_parameters()
    {
        $request = new RestRequestBuilder('users')
            ->withQueryParameters([
                'search' => 'my-query',
                'first_name' => ['John', 'Jack'],
            ])
            ->make();

        $this->assertSame('my-query', $request->queryParameters->get('search'));
        $this->assertSame(['John', 'Jack'], $request->queryParameters->get('first_name'));
    }

    public function test_making_a_request_with_a_segment()
    {
        $request = new RestRequestBuilder('users/{id}')
            ->withSegment('id', 19)
            ->make();

        $this->assertSame(19, $request->segments->get('id'));
    }

    public function test_making_a_request_builder_from_a_request()
    {
        $request = new RestRequest(
            resource: 'users/{id}',
            method: Method::PUT,
            queryParameters: new ImmutableQueryParameterCollection([
                'first_name' => ['John'],
            ]),
            headers: new ImmutableHeaderCollection([
                'Content-Type' => ['application/xml'],
            ]),
            segments: new ImmutableSegmentCollection([
                'id' => 12,
            ]),
            body: 'Hello World',
            authenticator: new BearerAuthenticator('test-token'),
        );

        $requestBuilder = RestRequestBuilder::fromRequest($request);

        $this->assertInstanceOf(RestRequestBuilder::class, $requestBuilder);
        $this->assertEquals($request->resource, $requestBuilder->resource);
        $this->assertEquals($request->method, $requestBuilder->method);
        $this->assertEqualsCanonicalizing($request->queryParameters->all(), $requestBuilder->queryParameters->all());
        $this->assertEqualsCanonicalizing($request->headers->all(), $requestBuilder->headers->all());
        $this->assertEqualsCanonicalizing($request->segments->all(), $requestBuilder->segments->all());
        $this->assertEquals($request->body, $requestBuilder->body);
        $this->assertEquals($request->authenticator, $requestBuilder->authenticator);
    }
}
