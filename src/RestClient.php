<?php

declare(strict_types=1);

namespace Rest\Http;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Rest\Http\Request\RestRequest;
use Rest\Http\Response\RestResponse;

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
        $request = RestRequest::new($uri)
            ->setMethod(Method::GET)
            ->addQueryParameters($query)
            ->addHeaders($headers)
            ->setBody($body);

        return $this->execute($request);
    }

    /**
     * @param array<string,string> $query
     * @param array<string,string> $headers
     */
    public function executePost(string $uri, array $query = [], array $headers = [], ?string $body = null): RestResponse
    {
        $request = RestRequest::new($uri)
            ->setMethod(Method::POST)
            ->addQueryParameters($query)
            ->addHeaders($headers)
            ->setBody($body);

        return $this->execute($request);
    }

    /**
     * @param array<string,string> $query
     * @param array<string,string> $headers
     */
    public function executeDelete(string $uri, array $query = [], array $headers = [], ?string $body = null): RestResponse
    {
        $request = RestRequest::new($uri)
            ->setMethod(Method::DELETE)
            ->addQueryParameters($query)
            ->addHeaders($headers)
            ->setBody($body);

        return $this->execute($request);
    }

    /**
     * @param array<string,string> $query
     * @param array<string,string> $headers
     */
    public function executePut(string $uri, array $query = [], array $headers = [], ?string $body = null): RestResponse
    {
        $request = RestRequest::new($uri)
            ->setMethod(Method::PUT)
            ->addQueryParameters($query)
            ->addHeaders($headers)
            ->setBody($body);

        return $this->execute($request);
    }

    /**
     * @param array<string,string> $query
     * @param array<string,string> $headers
     */
    public function executePatch(string $uri, array $query = [], array $headers = [], ?string $body = null): RestResponse
    {
        $request = RestRequest::new($uri)
            ->setMethod(Method::PATCH)
            ->addQueryParameters($query)
            ->addHeaders($headers)
            ->setBody($body);

        return $this->execute($request);
    }

    /**
     * @param array<string,string> $query
     * @param array<string,string> $headers
     */
    public function executeHead(string $uri, array $query = [], array $headers = [], ?string $body = null): RestResponse
    {
        $request = RestRequest::new($uri)
            ->setMethod(Method::HEAD)
            ->addQueryParameters($query)
            ->addHeaders($headers)
            ->setBody($body);

        return $this->execute($request);
    }

    /**
     * @param array<string,string> $query
     * @param array<string,string> $headers
     */
    public function executeOptions(string $uri, array $query = [], array $headers = [], ?string $body = null): RestResponse
    {
        $request = RestRequest::new($uri)
            ->setMethod(Method::OPTIONS)
            ->addQueryParameters($query)
            ->addHeaders($headers)
            ->setBody($body);

        return $this->execute($request);
    }

    /**
     * @param array<string,string> $query
     * @param array<string,string> $headers
     */
    public function executeTrace(string $uri, array $query = [], array $headers = [], ?string $body = null): RestResponse
    {
        $request = RestRequest::new($uri)
            ->setMethod(Method::TRACE)
            ->addQueryParameters($query)
            ->addHeaders($headers)
            ->setBody($body);

        return $this->execute($request);
    }

    public function execute(RestRequest $request): RestResponse
    {
        // If applicable, authenticate the request.
        $authenticator = $request->getAuthenticator() ?? $this->config->authenticator;

        if ($authenticator) {
            $authenticator->authenticate($this, $request);
        }

        // Convert the RestRequest object to a PSR Request.
        $psrRequest = $this->restRequestToPsrRequest($request);

        // Use the PSR client to send the PSR request.
        $psrResponse = $this->config->httpClient->sendRequest($psrRequest);

        // Convert the PSR Response to a RestResponse.
        return $this->psrResponseToRestResponse($request, $psrResponse);
    }

    private function restRequestToPsrRequest(RestRequest $request): RequestInterface
    {
        $uri = $this->assembleUri($request->getResource(), $request->getQueryParameters());

        $psrRequest = $this->config->requestFactory->createRequest(
            $request->getMethod()->value,
            $uri
        );

        foreach ($request->getHeaders() as $name => $value) {
            $psrRequest = $psrRequest->withHeader($name, $value);
        }

        if ($body = $request->getBody()) {
            $psrRequest = $psrRequest->withBody(
                $this->config->streamFactory->createStream($body)
            );
        }

        return $psrRequest;
    }

    private function psrResponseToRestResponse(RestRequest $request, ResponseInterface $response): RestResponse
    {
        $contents = $response->getBody()->getContents();
        $headers = $response->getHeaders();

        $response = new RestResponse(
            status: new Status($response->getStatusCode(), $response->getReasonPhrase()),
            request: $request
        );

        $response
            ->setContent($contents)
            ->setHeaders($headers);
    }

    /**
     * @param array<string,string> $query
     */
    private function assembleUri(string $uri, array $query = []): string
    {
        if (! $this->isUri($uri)) {
            $uri = $this->config->baseUri
                ? $this->config->baseUri . '/' . ltrim($uri, '/')
                : $uri;
        }

        if ($this->config->disableExplicitBooleans === false) {
            array_walk_recursive($query, function (&$value) {
                if (is_bool($value)) {
                    $value = $value ? 'true' : 'false';
                }
            });
        }

        return empty($query)
            ? $uri
            : "$uri?" . http_build_query($query);
    }

    private function isUri(string $uri): bool
    {
        $parsed = parse_url($uri);

        return
            (isset($parsed['scheme']) && isset($parsed['host']))
            || str_starts_with($uri, '//');
    }
}

