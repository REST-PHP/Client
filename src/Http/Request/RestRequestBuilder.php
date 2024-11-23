<?php

namespace Rest\Http\Request;

use Rest\Authenticators\Authenticator;
use Rest\Http\Method;
use Rest\Http\Parameters\ImmutableParameterCollection;
use Rest\Http\Parameters\MutableParameterCollection;
use Rest\Http\Segments\ImmutableSegmentCollection;
use Rest\Http\Segments\MutableSegmentCollection;

final class RestRequestBuilder
{
    private(set) string $resource;

    private(set) Method $method = Method::GET;

    private(set) ?Authenticator $authenticator = null;

    private(set) ?string $body = null;

    private(set) MutableParameterCollection $headers;

    private(set) MutableParameterCollection $queryParameters;

    private(set) MutableSegmentCollection $segments;

    public function __construct(string $resource)
    {
        $this->withResource($resource);

        $this->headers = new MutableParameterCollection();
        $this->queryParameters = new MutableParameterCollection();
        $this->segments = new MutableSegmentCollection();
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

    public function withHeader(string $name, string $value): self
    {
        $this->headers->add($name, $value);

        return $this;
    }

    /**
     * @param array<string,string|string[]> $headers
     */
    public function withHeaders(array $headers): self
    {
        $this->headers->merge($headers);

        return $this;
    }

    public function replaceHeader(string $name, string $value): self
    {
        $this->headers->set($name, $value);

        return $this;
    }

    /**
     * @param array<string,string|string[]> $headers
     */
    public function replaceHeaders(array $headers): self
    {
        $this->headers->replace($headers);

        return $this;
    }

    public function withQueryParameter(string $name, string $value): self
    {
        $this->queryParameters->add($name, $value);

        return $this;
    }

    /**
     * @param array<string,string|string[]> $queryParameters
     */
    public function withQueryParameters(array $queryParameters): self
    {
        $this->queryParameters->merge($queryParameters);

        return $this;
    }

    public function withSegment(string $name, string|int $value): self
    {
        $this->segments->set($name, $value);

        return $this;
    }

    public function make(): RestRequest
    {
        return new RestRequest(
            resource: $this->resource,
            method: $this->method,
            queryParameters: new ImmutableParameterCollection($this->queryParameters->all()),
            headers: new ImmutableParameterCollection($this->headers->all()),
            segments: new ImmutableSegmentCollection($this->segments->all()),
            body: $this->body,
            authenticator: $this->authenticator,
        );
    }
}