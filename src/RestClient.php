<?php

declare(strict_types=1);

namespace Rest;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Rest\Http\Method;
use Rest\Http\Parameters\ImmutableParameterCollection;
use Rest\Http\Request\RestRequest;
use Rest\Http\Request\RestRequestBuilder;
use Rest\Http\Response\RestResponse;
use Rest\Http\Response\RestResponseBuilder;
use Rest\Http\StatusCode;
use const PHP_QUERY_RFC3986;

final readonly class RestClient implements RestClientInterface
{
    public function __construct(public RestClientConfiguration $config = new RestClientConfiguration())
    {
    }

    /**
     * @param array<string,string> $query
     * @param array<string,string> $headers
     */
    public function executeGet(string $uri, array $query = [], array $headers = [], ?string $body = null): RestResponse
    {
        return $this->execute(
            new RestRequestBuilder($uri)
                ->withMethod(Method::GET)
                ->withQueryParameters($query)
                ->withHeaders($headers)
                ->withBody($body)
        );
    }

    /**
     * @param array<string,string> $query
     * @param array<string,string> $headers
     */
    public function executePost(string $uri, array $query = [], array $headers = [], ?string $body = null): RestResponse
    {
        return $this->execute(
            new RestRequestBuilder($uri)
                ->withMethod(Method::POST)
                ->withQueryParameters($query)
                ->withHeaders($headers)
                ->withBody($body)
        );
    }

    /**
     * @param array<string,string> $query
     * @param array<string,string> $headers
     */
    public function executeDelete(string $uri, array $query = [], array $headers = [], ?string $body = null): RestResponse
    {
        return $this->execute(
            new RestRequestBuilder($uri)
                ->withMethod(Method::DELETE)
                ->withQueryParameters($query)
                ->withHeaders($headers)
                ->withBody($body)
        );
    }

    /**
     * @param array<string,string> $query
     * @param array<string,string> $headers
     */
    public function executePut(string $uri, array $query = [], array $headers = [], ?string $body = null): RestResponse
    {
        return $this->execute(
            new RestRequestBuilder($uri)
                ->withMethod(Method::PUT)
                ->withQueryParameters($query)
                ->withHeaders($headers)
                ->withBody($body)
        );
    }

    /**
     * @param array<string,string> $query
     * @param array<string,string> $headers
     */
    public function executePatch(string $uri, array $query = [], array $headers = [], ?string $body = null): RestResponse
    {
        return $this->execute(
            new RestRequestBuilder($uri)
                ->withMethod(Method::PATCH)
                ->withQueryParameters($query)
                ->withHeaders($headers)
                ->withBody($body)
        );
    }

    /**
     * @param array<string,string> $query
     * @param array<string,string> $headers
     */
    public function executeHead(string $uri, array $query = [], array $headers = [], ?string $body = null): RestResponse
    {
        return $this->execute(
            new RestRequestBuilder($uri)
                ->withMethod(Method::HEAD)
                ->withQueryParameters($query)
                ->withHeaders($headers)
                ->withBody($body)
        );
    }

    /**
     * @param array<string,string> $query
     * @param array<string,string> $headers
     */
    public function executeOptions(string $uri, array $query = [], array $headers = [], ?string $body = null): RestResponse
    {
        return $this->execute(
            new RestRequestBuilder($uri)
                ->withMethod(Method::OPTIONS)
                ->withQueryParameters($query)
                ->withHeaders($headers)
                ->withBody($body)
        );
    }

    /**
     * @param array<string,string> $query
     * @param array<string,string> $headers
     */
    public function executeTrace(string $uri, array $query = [], array $headers = [], ?string $body = null): RestResponse
    {
        return $this->execute(
            new RestRequestBuilder($uri)
                ->withMethod(Method::TRACE)
                ->withQueryParameters($query)
                ->withHeaders($headers)
                ->withBody($body)
        );
    }

    public function execute(RestRequestBuilder $request, ?string $deserialize = null): RestResponse
    {
        // If applicable, authenticate the request.
        $authenticator = $request->authenticator ?? $this->config->authenticator;
        $authenticator?->authenticate($this, $request);

        // Convert the RestRequest object to a PSR Request.
        $request = $request->make();
        $psrRequest = $this->restRequestToPsrRequest($request);

        // Use the PSR client to send the PSR request.
        $psrResponse = $this->config->httpClient->sendRequest($psrRequest);

        // Convert the PSR Response to a RestResponse.
        return $this->psrResponseToRestResponse($request, $psrResponse);
    }

    private function restRequestToPsrRequest(RestRequest $request): RequestInterface
    {
        $uri = $this->assembleUri($request->resource, $request->queryParameters);
        $uri = $request->segments->apply($uri);

        $psrRequest = $this->config->requestFactory->createRequest(
            method: $request->method->value,
            uri: $uri
        );

        foreach ($request->headers->all() as $name => $value) {
            $psrRequest = $psrRequest->withHeader($name, $value);
        }

        if ($request->body) {
            $psrRequest = $psrRequest->withBody(
                $this->config->streamFactory->createStream($request->body)
            );
        }

        return $psrRequest;
    }

    private function psrResponseToRestResponse(RestRequest $request, ResponseInterface $response): RestResponse
    {
        $statusCode = StatusCode::from($response->getStatusCode());
        $reasonPhrase = $response->getReasonPhrase();
        $contents = $response->getBody()->getContents();
        $headers = $response->getHeaders();

        return new RestResponseBuilder($request, $statusCode)
            ->withReasonPhrase($reasonPhrase)
            ->withBody($contents)
            ->withHeaders($headers)
            ->make();
    }

    private function assembleUri(string $uri, ImmutableParameterCollection $queryParameters): string
    {
        // If the resource doesn't have a protocol, prepend our base uri.
        if (! $this->isUri($uri)) {
            $uri = $this->config->baseUri
                ? $this->config->baseUri . '/' . ltrim($uri, '/')
                : $uri;
        }

        $query = $queryParameters->all();

        if ($this->config->disableExplicitBooleans === false) {
            // Convert values like "0" and "1" to "true" or "false" in the uri.
            array_walk_recursive($query, function (&$value) {
                $isBool = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) !== null;

                if ($isBool) {
                    $value = $value ? 'true' : 'false';
                }
            });
        }

        // Flatten non-array parameters.
        $query = array_map(fn ($value) => count($value) === 1 ? $value[0] : $value, $query);

        return empty($query)
            ? $uri
            : "$uri?" . http_build_query($query, encoding_type: PHP_QUERY_RFC3986);
    }

    private function isUri(string $uri): bool
    {
        $parsed = parse_url($uri);

        return
            (isset($parsed['scheme']) && isset($parsed['host']))
            || str_starts_with($uri, '//');
    }
}

