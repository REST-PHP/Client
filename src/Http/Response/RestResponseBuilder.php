<?php

namespace Rest\Http\Response;

use Rest\Http\Parameters\ImmutableParameterCollection;
use Rest\Http\Parameters\MutableParameterCollection;
use Rest\Http\Request\RestRequest;
use Rest\Http\StatusCode;

final class RestResponseBuilder
{
    private(set) RestRequest $request;

    private(set) StatusCode $statusCode;

    private(set) string $reasonPhrase = '';

    private(set) MutableParameterCollection $headers;

    private(set) ?string $body = null;

    public static function fromResponse(RestResponse $response): self
    {
        return new self($response->request, $response->statusCode)
            ->withReasonPhrase($response->reasonPhrase)
            ->withHeaders($response->headers->all())
            ->withBody($response->body);
    }

    public function __construct(RestRequest $request, StatusCode $statusCode = StatusCode::OK)
    {
        $this->headers = new MutableParameterCollection();

        $this
            ->withRequest($request)
            ->withStatusCode($statusCode);
    }

    public function withRequest(RestRequest $request): self
    {
        $this->request = $request;

        return $this;
    }

    public function withStatusCode(StatusCode $status): self
    {
        $this->statusCode = $status;

        return $this;
    }

    public function withReasonPhrase(string $reasonPhrase): self
    {
        $this->reasonPhrase = $reasonPhrase;

        return $this;
    }

    /**
     * @param array<string,string|string[]> $headers
     * @return $this
     */
    public function withHeaders(array $headers): self
    {
        $this->headers->merge($headers);

        return $this;
    }

    public function withBody(?string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function make(): RestResponse
    {
        return new RestResponse(
            request: $this->request,
            statusCode: $this->statusCode,
            reasonPhrase: $this->reasonPhrase,
            headers: new ImmutableParameterCollection($this->headers->all()),
            body: $this->body,
        );
    }
}