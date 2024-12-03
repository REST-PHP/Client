<?php

declare(strict_types=1);

namespace Rest\Tests\Http\Response;

use PHPUnit\Framework\TestCase;
use Rest\Http\Components\Headers\ImmutableHeaderCollection;
use Rest\Http\Request\RestRequestBuilder;
use Rest\Http\Response\RestResponse;
use Rest\Http\Response\RestResponseBuilder;
use Rest\Http\StatusCode;

/**
 * @internal
 */
class ResponseBuilderTest extends TestCase
{
    public function test_making_a_response()
    {
        $request = new RestRequestBuilder('companies')->make();
        $response = new RestResponseBuilder($request, StatusCode::OK)->make();

        $this->assertInstanceOf(RestResponse::class, $response);
        $this->assertSame($request, $response->request);
        $this->assertSame(StatusCode::OK, $response->statusCode);
    }

    public function test_making_a_response_with_a_reason_phrase()
    {
        $request = new RestRequestBuilder('companies')->make();
        $response = new RestResponseBuilder($request, StatusCode::OK)
            ->withReasonPhrase('Testing')
            ->make();

        $this->assertSame('Testing', $response->reasonPhrase);
    }

    public function test_making_a_response_with_a_body()
    {
        $request = new RestRequestBuilder('companies')->make();
        $response = new RestResponseBuilder($request, StatusCode::OK)
            ->withBody('Hello, world!')
            ->make();

        $this->assertSame('Hello, world!', $response->body);
    }

    public function test_making_a_response_builder_from_a_response()
    {
        $response = new RestResponse(
            request: new RestRequestBuilder('companies')->make(),
            statusCode: StatusCode::IM_A_TEAPOT,
            reasonPhrase: 'Testing',
            headers: new ImmutableHeaderCollection([
                'Content-Type' => ['application/xml'],
            ]),
            body: 'Hello, world!'
        );

        $responseBuilder = RestResponseBuilder::fromResponse($response);

        $this->assertInstanceOf(RestResponse::class, $response);
        $this->assertEquals($response->request, $responseBuilder->request);
        $this->assertEquals($response->statusCode, $responseBuilder->statusCode);
        $this->assertEquals($response->reasonPhrase, $responseBuilder->reasonPhrase);
        $this->assertEqualsCanonicalizing($response->headers->all(), $responseBuilder->headers->all());
        $this->assertEquals($response->body, $responseBuilder->body);
    }
}
