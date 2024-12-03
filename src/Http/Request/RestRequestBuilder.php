<?php

namespace Rest\Http\Request;

use Rest\Authenticators\Authenticator;
use Rest\Http\Components\Headers\MutableHeaderCollection;
use Rest\Http\Components\QueryParameters\MutableQueryParameterCollection;
use Rest\Http\Components\Segments\MutableSegmentCollection;
use Rest\Http\Method;

final class RestRequestBuilder
{
    private(set) string $resource;

    private(set) Method $method = Method::GET;

    private(set) ?Authenticator $authenticator = null;

    private(set) ?string $body = null;

    private(set) MutableHeaderCollection $headers;

    private(set) MutableQueryParameterCollection $queryParameters;

    private(set) MutableSegmentCollection $segments;

    public static function fromRequest(RestRequest $request): RestRequestBuilder
    {
        return new self($request->resource)
            ->withAuthenticator($request->authenticator)
            ->withBody($request->body)
            ->withHeaders($request->headers->all())
            ->withQueryParameters($request->queryParameters->all())
            ->withMethod($request->method)
            ->withSegments($request->segments->all());
    }

    public function __construct(string $resource)
    {
        $this->headers = new MutableHeaderCollection();
        $this->queryParameters = new MutableQueryParameterCollection();
        $this->segments = new MutableSegmentCollection();

        $this->withResource($resource);
    }

    public function withResource(string $resource): self
    {
        $this->resource = $resource;

        return $this;
    }

    public function withMethod(Method $method): self
    {
        $this->method = $method;

        return $this;
    }

    public function withAuthenticator(?Authenticator $authenticator): self
    {
        $this->authenticator = $authenticator;

        return $this;
    }

    public function withBody(?string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function withHeader(string $name, bool|float|int|string $value): self
    {
        $this->headers->add($name, $value);

        return $this;
    }

    /**
     * @param array<string,scalar|scalar[]> $headers
     */
    public function withHeaders(array $headers): self
    {
        $this->headers->merge($headers);

        return $this;
    }

    public function replaceHeader(string $name, bool|float|int|string $value): self
    {
        $this->headers->set($name, $value);

        return $this;
    }

    /**
     * @param array<string,scalar|scalar[]> $headers
     */
    public function replaceHeaders(array $headers): self
    {
        $this->headers->replace($headers);

        return $this;
    }

    public function withQueryParameter(string $name, bool|float|int|string $value): self
    {
        $this->queryParameters->add($name, $value);

        return $this;
    }

    /**
     * @param array<string,scalar|scalar[]> $queryParameters
     */
    public function withQueryParameters(array $queryParameters): self
    {
        $this->queryParameters->merge($queryParameters);

        return $this;
    }

    /**
     * @param array<string,scalar|scalar[]> $queryParameters
     */
    public function replaceQueryParameters(array $queryParameters): self
    {
        $this->queryParameters->replace($queryParameters);

        return $this;
    }

    public function withSegment(string $name, string|int $value): self
    {
        $this->segments->set($name, $value);

        return $this;
    }

    /**
     * @param array<string,string|int> $segments
     */
    public function withSegments(array $segments): self
    {
        foreach ($segments as $segment => $value) {
            $this->withSegment($segment, $value);
        }

        return $this;
    }

    public function make(): RestRequest
    {
        return new RestRequest(
            resource: $this->resource,
            method: $this->method,
            queryParameters: $this->queryParameters->toImmutable(),
            headers: $this->headers->toImmutable(),
            segments: $this->segments->toImmutable(),
            body: $this->body,
            authenticator: $this->authenticator,
        );
    }
}