<?php

declare(strict_types=1);

namespace Planka\Bridge\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Planka\Bridge\Config;
use Planka\Bridge\PlankaClient;
use Planka\Bridge\TransportClients\Client;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

abstract class AbstractUnitTestCase extends TestCase
{
    protected Config $config;

    protected function setUp(): void
    {
        parent::setUp();
        $this->config = new Config(
            user: 'test@example.com',
            password: 'password',
            baseUri: 'http://localhost',
            port: 3000,
        );
        $this->config->setAuthToken('test-jwt-token');
    }

    protected function createMockClient(string $fixturePath, int $statusCode = 200, array $headers = []): PlankaClient
    {
        $fullPath = __DIR__ . '/../Fixtures/' . ltrim($fixturePath, '/');
        if (!file_exists($fullPath)) {
            throw new \InvalidArgumentException("Fixture file not found: {$fullPath}");
        }

        $content = file_get_contents($fullPath);
        $mockResponse = new MockResponse($content, [
            'http_code' => $statusCode,
            'response_headers' => array_merge(['content-type' => 'application/json'], $headers),
        ]);

        $mockHttpClient = new MockHttpClient($mockResponse);
        $transportClient = new Client($this->config, $mockHttpClient);

        return new PlankaClient($this->config, $transportClient);
    }

    protected function createMockClientWithResponse(string $body, int $statusCode = 200, array $headers = []): PlankaClient
    {
        $mockResponse = new MockResponse($body, [
            'http_code' => $statusCode,
            'response_headers' => array_merge(['content-type' => 'application/json'], $headers),
        ]);

        $mockHttpClient = new MockHttpClient($mockResponse);
        $transportClient = new Client($this->config, $mockHttpClient);

        return new PlankaClient($this->config, $transportClient);
    }
}
