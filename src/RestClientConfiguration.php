<?php

declare(strict_types=1);

namespace Rest\Http;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use PsrDiscovery\Discover;

final readonly class RestClientConfiguration
{
    public ClientInterface $psrClient;
    public RequestFactoryInterface $requestFactory;
    public StreamFactoryInterface $streamFactory;
    public bool $disableExplicitBooleans;

    public function __construct(
        ?ClientInterface $psrClient = null,
        ?RequestFactoryInterface $requestFactory = null,
        ?StreamFactoryInterface $streamFactory = null,
        bool $disableExplicitBooleans = false,
    ) {
        // @phpstan-ignore assign.propertyType
        $this->psrClient = $psrClient ?? Discover::httpClient();
        // @phpstan-ignore assign.propertyType
        $this->requestFactory = $requestFactory ?? Discover::httpRequestFactory();
        // @phpstan-ignore assign.propertyType
        $this->streamFactory = $streamFactory ?? Discover::httpStreamFactory();
        $this->disableExplicitBooleans = $disableExplicitBooleans;
    }
}
