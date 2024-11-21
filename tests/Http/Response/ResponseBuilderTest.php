<?php

namespace Rest\Tests\Http\Response;

use PHPUnit\Framework\TestCase;
use Rest\Http\Request\RestRequestBuilder;
use Rest\Http\Response\RestResponse;
use Rest\Http\Response\RestResponseBuilder;
use Rest\Http\StatusCode;

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
}