<?php

namespace Rest\Http\Response;

use Rest\Http\Request\RestRequest;
use Rest\Http\StatusCode;

final class RestResponseBuilder
{
    private(set) RestRequest $request;

    private(set) StatusCode $statusCode;

    private(set) string $reasonPhrase = '';

    private(set) ?string $body = null;

    public function __construct(RestRequest $request, StatusCode $statusCode = StatusCode::OK)
    {
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
            body: $this->body,
        );
    }
}