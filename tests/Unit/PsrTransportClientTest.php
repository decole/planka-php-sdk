<?php

declare(strict_types=1);

namespace Planka\Bridge\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Planka\Bridge\Config;
use Planka\Bridge\Exceptions\PlankaNotFoundException;
use Planka\Bridge\Inputs\CardPatchInput;
use Planka\Bridge\PlankaClient;
use Planka\Bridge\TransportClients\PsrTransportClient;
use Planka\Bridge\Views\Dto\Card\CardDto;
use Psr\Http\Client\ClientInterface as PsrClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;

final class PsrTransportClientTest extends TestCase
{
    public function testPsrTransportClientGetAndHydrate(): void
    {
        $fixturePath = __DIR__ . '/../Fixtures/Card/card_create.json';
        $fixtureContent = file_get_contents($fixturePath);

        $config = new Config(
            baseUri: 'http://localhost',
            port: 3000,
            apiKey: 'test-api-key',
        );

        $mockStream = $this->createMock(StreamInterface::class);
        $mockStream->method('__toString')->willReturn($fixtureContent);

        $mockResponse = $this->createMock(PsrResponseInterface::class);
        $mockResponse->method('getStatusCode')->willReturn(200);
        $mockResponse->method('getBody')->willReturn($mockStream);

        $mockRequest = $this->createMock(RequestInterface::class);
        $mockRequest->method('withHeader')->willReturnSelf();
        $mockRequest->method('withBody')->willReturnSelf();

        $mockRequestFactory = $this->createMock(RequestFactoryInterface::class);
        $mockRequestFactory->method('createRequest')->willReturn($mockRequest);

        $mockStreamFactory = $this->createMock(StreamFactoryInterface::class);
        $mockStreamFactory->method('createStream')->willReturn($mockStream);

        $mockPsrClient = $this->createMock(PsrClientInterface::class);
        $mockPsrClient->expects($this->once())
            ->method('sendRequest')
            ->willReturn($mockResponse);

        $transport = new PsrTransportClient(
            config: $config,
            httpClient: $mockPsrClient,
            requestFactory: $mockRequestFactory,
            streamFactory: $mockStreamFactory,
        );

        $client = new PlankaClient($config, $transport);
        $card = $client->card()->get('1854744390124176969');

        $this->assertInstanceOf(CardDto::class, $card);
        $this->assertEquals('1854744390124176969', $card->id);
    }

    public function testPsrTransportClientPatching(): void
    {
        $fixturePath = __DIR__ . '/../Fixtures/Card/card_create.json';
        $fixtureContent = file_get_contents($fixturePath);

        $config = new Config(
            baseUri: 'http://localhost',
            port: 3000,
            apiKey: 'test-api-key',
        );

        $mockStream = $this->createMock(StreamInterface::class);
        $mockStream->method('__toString')->willReturn($fixtureContent);

        $mockResponse = $this->createMock(PsrResponseInterface::class);
        $mockResponse->method('getStatusCode')->willReturn(200);
        $mockResponse->method('getBody')->willReturn($mockStream);

        $mockRequest = $this->createMock(RequestInterface::class);
        $mockRequest->method('withHeader')->willReturnSelf();
        $mockRequest->method('withBody')->willReturnSelf();

        $mockRequestFactory = $this->createMock(RequestFactoryInterface::class);
        $mockRequestFactory->method('createRequest')->willReturn($mockRequest);

        $mockStreamFactory = $this->createMock(StreamFactoryInterface::class);
        $mockStreamFactory->method('createStream')->willReturn($mockStream);

        $mockPsrClient = $this->createMock(PsrClientInterface::class);
        $mockPsrClient->expects($this->once())
            ->method('sendRequest')
            ->willReturn($mockResponse);

        $transport = new PsrTransportClient(
            config: $config,
            httpClient: $mockPsrClient,
            requestFactory: $mockRequestFactory,
            streamFactory: $mockStreamFactory,
        );

        $client = new PlankaClient($config, $transport);
        $card = $client->card()->patching('1854744390124176969', new CardPatchInput(name: 'Updated Name'));

        $this->assertInstanceOf(CardDto::class, $card);
        $this->assertEquals('1854744390124176969', $card->id);
    }

    public function testPsrTransportClientErrorHandling(): void
    {
        $config = new Config(
            baseUri: 'http://localhost',
            port: 3000,
            apiKey: 'test-api-key',
        );

        $mockStream = $this->createMock(StreamInterface::class);
        $mockStream->method('__toString')->willReturn(json_encode(['message' => 'Card not found']));

        $mockResponse = $this->createMock(PsrResponseInterface::class);
        $mockResponse->method('getStatusCode')->willReturn(404);
        $mockResponse->method('getBody')->willReturn($mockStream);

        $mockRequest = $this->createMock(RequestInterface::class);
        $mockRequest->method('withHeader')->willReturnSelf();

        $mockRequestFactory = $this->createMock(RequestFactoryInterface::class);
        $mockRequestFactory->method('createRequest')->willReturn($mockRequest);

        $mockPsrClient = $this->createMock(PsrClientInterface::class);
        $mockPsrClient->method('sendRequest')->willReturn($mockResponse);

        $transport = new PsrTransportClient(
            config: $config,
            httpClient: $mockPsrClient,
            requestFactory: $mockRequestFactory,
        );

        $client = new PlankaClient($config, $transport);

        $this->expectException(PlankaNotFoundException::class);
        $this->expectExceptionCode(404);

        $client->card()->get('invalid-id');
    }
}
