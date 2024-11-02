<?php

declare(strict_types=1);

namespace Rest\Http\Request;

use Rest\Http\Authenticators\Authenticator;
use Rest\Http\Method;

final class RestRequest
{
    private string $resource;

    private Method $method;

    private ?string $body = null;

    private ?Authenticator $authenticator = null;

    private array $headers = [];

    private array $queryParameters = [];

    public static function new(string $resource): self
    {
        return new self($resource);
    }

    public function __construct(string $resource, Method $method = Method::GET)
    {
        $this
            ->setResource($resource)
            ->setMethod($method);
    }

    public function getResource(): string
    {
        return $this->resource;
    }

    public function setResource(string $resource): self
    {
        $this->resource = $resource;

        return $this;
    }

    public function getMethod(): Method
    {
        return $this->method;
    }

    public function setMethod(Method $method): self
    {
        $this->method = $method;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(?string $body): self
    {
        $this->body = $body;
    }

    public function getAuthenticator(): ?Authenticator
    {
        return $this->authenticator;
    }

    public function setAuthenticator(?Authenticator $authenticator): self
    {
        $this->authenticator = $authenticator;

        return $this;
    }

    public function addHeaders(array $headers): self
    {
        foreach ($headers as $name => $value) {
            $this->addHeader($name, $value);
        }

        return $this;
    }

    public function setHeaders(array $headers): self
    {
        $this->headers = [];
        $this->addHeaders($headers);

        return $this;
    }

    public function addHeader(string $name, string $value): self
    {
        if (! isset($this->headers[$name])) {
            $this->headers[$name] = [];
        }

        $this->headers[$name][] = $value;

        return $this;
    }

    public function replaceHeader(string $name, string $value): self
    {
        $this->headers[$name] = [$value];

        return $this;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function addQueryParameters(array $parameters): self
    {
        foreach ($parameters as $name => $value) {
            $this->addQueryParameter($name, $value);
        }

        return $this;
    }

    public function setQueryParameters(array $parameters): self
    {
        $this->queryParameters = [];
        $this->addQueryParameters($parameters);

        return $this;
    }

    public function addQueryParameter(string $name, string $value): self
    {
        if (! isset($this->queryParameters[$name])) {
            $this->queryParameters[$name] = [];
        }

        $this->queryParameters[$name][] = $value;

        return $this;
    }

    public function replaceQueryParameter(string $name, string $value): self
    {
        $this->queryParameters[$name] = [$value];

        return $this;
    }

    public function getQueryParameters(): array
    {
        return $this->queryParameters;
    }
}