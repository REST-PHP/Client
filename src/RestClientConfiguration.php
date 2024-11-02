<?php

declare(strict_types=1);

namespace Rest\Http;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use PsrDiscovery\Discover;
use Rest\Http\Authenticators\Authenticator;
use RuntimeException;

final readonly class RestClientConfiguration
{
    public ?string $baseUri;
    public ClientInterface $httpClient;
    public RequestFactoryInterface $requestFactory;
    public StreamFactoryInterface $streamFactory;
    public ?Authenticator $authenticator;
    public bool $disableExplicitBooleans;

    public function __construct(
        ?string $baseUri = null,
        ?ClientInterface $httpClient = null,
        ?RequestFactoryInterface $requestFactory = null,
        ?StreamFactoryInterface $streamFactory = null,
        ?Authenticator $authenticator = null,
        bool $disableExplicitBooleans = false,
    ) {
        $this->baseUri = $baseUri ? rtrim($baseUri, '/') : null;
        $this->httpClient = $httpClient ?? $this->initializeHttpClient();
        $this->requestFactory = $requestFactory ?? $this->initializeRequestFactory();
        $this->streamFactory = $streamFactory ?? $this->initializeStreamFactory();

        $this->authenticator = $authenticator;
        $this->disableExplicitBooleans = $disableExplicitBooleans;
    }

    private function initializeHttpClient(): ClientInterface
    {
        return Discover::httpClient() ?? throw new RuntimeException(
            'The PSR http client cannot be null. Please ensure that it is properly initialized.'
        );
    }

    private function initializeRequestFactory(): RequestFactoryInterface
    {
        return Discover::httpRequestFactory() ?? throw new RuntimeException(
            'The PSR request factory cannot be null. Please ensure that it is properly initialized.'
        );
    }

    private function initializeStreamFactory(): StreamFactoryInterface
    {
        return Discover::httpStreamFactory() ?? throw new RuntimeException(
            'The PSR stream factory cannot be null. Please ensure that it is properly initialized.'
        );
    }
}
