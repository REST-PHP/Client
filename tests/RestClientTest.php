<?php

declare(strict_types=1);

namespace Rest\Http\Tests;

use AidanCasey\MockClient\Client;
use PHPUnit\Framework\TestCase;
use Rest\Http\RestClient;
use Rest\Http\RestClientConfiguration;

/**
 * @internal
 */
final class RestClientTest extends TestCase
{
    private Client $client;

    private RestClientConfiguration $configuration;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = new Client();
        $this->configuration = new RestClientConfiguration(httpClient: $this->client);
    }

    public function test_explicit_boolean_query_parameters()
    {
        $restClient = new RestClient($this->configuration);

        $restClient->executeGet('https://testing.com', [
            'enable_booleans' => true,
        ]);

        $this->client->assertUri('https://testing.com?enable_booleans=true');
    }

    public function test_disabling_explicit_boolean_query_parameters()
    {
        $restClient = new RestClient(new RestClientConfiguration(
            httpClient: $this->client,
            disableExplicitBooleans: true,
        ));

        $restClient->executeGet('https://testing.com', [
            'enable_booleans' => true,
        ]);

        $this->client->assertUri('https://testing.com?enable_booleans=1');
    }

    public function test_setting_headers()
    {
        $restClient = new RestClient($this->configuration);

        $restClient->executeGet('https://testing.com', headers: [
            'Accept' => 'application/json',
        ]);

        $this->client->assertHeaderEquals('Accept', 'application/json');
    }

    public function test_setting_body_content()
    {
        $restClient = new RestClient($this->configuration);

        $restClient->executePost('https://testing.com', body: json_encode([
            'name' => 'Dwight Schrute',
        ]));

        $this->client->assertBodyIs('{"name":"Dwight Schrute"}');
    }

    public function test_sending_get_request()
    {
        $restClient = new RestClient($this->configuration);

        $restClient->executeGet('https://testing.com');

        $this->client->assertMethod('GET');
    }

    public function test_sending_post_request()
    {
        $restClient = new RestClient($this->configuration);

        $restClient->executePost('https://testing.com');

        $this->client->assertMethod('POST');
    }

    public function test_sending_delete_request()
    {
        $restClient = new RestClient($this->configuration);

        $restClient->executeDelete('https://testing.com');

        $this->client->assertMethod('DELETE');
    }

    public function test_sending_put_request()
    {
        $restClient = new RestClient($this->configuration);

        $restClient->executePut('https://testing.com');

        $this->client->assertMethod('PUT');
    }

    public function test_sending_patch_request()
    {
        $restClient = new RestClient($this->configuration);

        $restClient->executePatch('https://testing.com');

        $this->client->assertMethod('PATCH');
    }

    public function test_sending_head_request()
    {
        $restClient = new RestClient($this->configuration);

        $restClient->executeHead('https://testing.com');

        $this->client->assertMethod('HEAD');
    }

    public function test_sending_options_request()
    {
        $restClient = new RestClient($this->configuration);

        $restClient->executeOptions('https://testing.com');

        $this->client->assertMethod('OPTIONS');
    }

    public function test_sending_trace_request()
    {
        $restClient = new RestClient($this->configuration);

        $restClient->executeTrace('https://testing.com');

        $this->client->assertMethod('TRACE');
    }

    public function test_base_uri_with_path()
    {
        $restClient = new RestClient(new RestClientConfiguration(
            baseUri: 'https://testing.com',
            httpClient: $this->client,
        ));

        $restClient->executeGet('/testing');

        $this->client->assertUri('https://testing.com/testing');
    }

    public function test_base_uri_with_full_uri()
    {
        $restClient = new RestClient(new RestClientConfiguration(
            baseUri: 'https://testing.com',
            httpClient: $this->client,
        ));

        $restClient->executeGet('https://example.org');

        $this->client->assertUri('https://example.org');
    }
}
