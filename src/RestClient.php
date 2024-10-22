<?php

declare(strict_types=1);

namespace Rest\Http;

final readonly class RestClient
{
    public function __construct(private RestClientConfiguration $config = new RestClientConfiguration())
    {
    }

    /**
     * @param array<string,string> $query
     * @param array<string,string> $headers
     */
    public function executeGet(string $uri, array $query = [], array $headers = [], ?string $body = null): Response
    {
        return $this->send(
            method: Method::GET,
            uri: $uri,
            query: $query,
            headers: $headers,
            body: $body
        );
    }

    /**
     * @param array<string,string> $query
     * @param array<string,string> $headers
     */
    public function executePost(string $uri, array $query = [], array $headers = [], ?string $body = null): Response
    {
        return $this->send(
            method: Method::POST,
            uri: $uri,
            query: $query,
            headers: $headers,
            body: $body
        );
    }

    /**
     * @param array<string,string> $query
     * @param array<string,string> $headers
     */
    public function executeDelete(string $uri, array $query = [], array $headers = [], ?string $body = null): Response
    {
        return $this->send(
            method: Method::DELETE,
            uri: $uri,
            query: $query,
            headers: $headers,
            body: $body
        );
    }

    /**
     * @param array<string,string> $query
     * @param array<string,string> $headers
     */
    public function executePut(string $uri, array $query = [], array $headers = [], ?string $body = null): Response
    {
        return $this->send(
            method: Method::PUT,
            uri: $uri,
            query: $query,
            headers: $headers,
            body: $body
        );
    }

    /**
     * @param array<string,string> $query
     * @param array<string,string> $headers
     */
    public function executePatch(string $uri, array $query = [], array $headers = [], ?string $body = null): Response
    {
        return $this->send(
            method: Method::PATCH,
            uri: $uri,
            query: $query,
            headers: $headers,
            body: $body
        );
    }

    /**
     * @param array<string,string> $query
     * @param array<string,string> $headers
     */
    public function executeHead(string $uri, array $query = [], array $headers = [], ?string $body = null): Response
    {
        return $this->send(
            method: Method::HEAD,
            uri: $uri,
            query: $query,
            headers: $headers,
            body: $body
        );
    }

    /**
     * @param array<string,string> $query
     * @param array<string,string> $headers
     */
    public function executeOptions(string $uri, array $query = [], array $headers = [], ?string $body = null): Response
    {
        return $this->send(
            method: Method::OPTIONS,
            uri: $uri,
            query: $query,
            headers: $headers,
            body: $body
        );
    }

    /**
     * @param array<string,string> $query
     * @param array<string,string> $headers
     */
    public function executeTrace(string $uri, array $query = [], array $headers = [], ?string $body = null): Response
    {
        return $this->send(
            method: Method::TRACE,
            uri: $uri,
            query: $query,
            headers: $headers,
            body: $body
        );
    }

    /**
     * @param array<string,string> $query
     * @param array<string,string> $headers
     */
    private function send(Method $method, string $uri, array $query = [], array $headers = [], ?string $body = null): Response
    {
        // First, assemble the URI.
        $uri = $this->assembleUri($uri, $query);
        $request = $this->config->requestFactory->createRequest($method->value, $uri);

        // Attach the headers to the request.
        foreach ($headers as $key => $value) {
            $request = $request->withHeader($key, $value);
        }

        // Attach the body to the request.
        if ($body !== null) {
            $request = $request->withBody(
                $this->config->streamFactory->createStream($body)
            );
        }

        // Send it!
        $response = $this->config->psrClient->sendRequest($request);
        $contents = $response->getBody()->getContents();

        return new Response(
            contentType: $response->getHeaderLine('Content-Type'),
            content: $contents,
            status: new Status(
                code: $response->getStatusCode(),
                message: $response->getReasonPhrase(),
            ),
            headers: $response->getHeaders(),
        );
    }

    /**
     * @param array<string,string> $query
     */
    private function assembleUri(string $uri, array $query = []): string
    {
        if ($this->config->disableExplicitBooleans === false) {
            array_walk_recursive($query, function (&$value) {
                if (is_bool($value)) {
                    $value = $value ? 'true' : 'false';
                }
            });
        }

        return "$uri?" . http_build_query($query);
    }
}
