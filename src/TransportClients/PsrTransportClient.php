<?php

declare(strict_types=1);

namespace Planka\Bridge\TransportClients;

use Planka\Bridge\Config;
use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Factory\OutputInterface;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface as PsrClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;

final class PsrTransportClient implements TransportClientInterface
{
    use TransportClientTrait;

    public function __construct(
        private readonly Config $config,
        private readonly PsrClientInterface $httpClient,
        private readonly RequestFactoryInterface $requestFactory,
        private readonly ?StreamFactoryInterface $streamFactory = null,
    ) {}

    public function get(ActionInterface $action): mixed
    {
        return $this->send('GET', $action);
    }

    public function post(ActionInterface $action): mixed
    {
        return $this->send('POST', $action);
    }

    public function patch(ActionInterface $action): mixed
    {
        return $this->send('PATCH', $action);
    }

    public function delete(ActionInterface $action): mixed
    {
        return $this->send('DELETE', $action);
    }

    /**
     * @throws ClientExceptionInterface
     * @throws \JsonException
     */
    private function send(string $method, ActionInterface $action): mixed
    {
        $url = $this->buildUrl($this->config, $action->url());
        $request = $this->requestFactory->createRequest($method, $url);

        $options = $action->getOptions();

        if ($action instanceof AuthenticateInterface) {
            $apiKey = $this->config->getApiKey();
            $authToken = $this->config->getAuthToken();

            if (null !== $apiKey) {
                $request = $request->withHeader('X-Api-Key', $apiKey);
            } elseif (null !== $authToken) {
                $request = $request->withHeader('Authorization', 'Bearer ' . $authToken);
            }
        }

        if (isset($options['headers']) && is_array($options['headers'])) {
            foreach ($options['headers'] as $headerName => $headerValue) {
                if (is_string($headerName) && is_string($headerValue)) {
                    $request = $request->withHeader($headerName, $headerValue);
                } elseif (is_int($headerName) && is_string($headerValue)) {
                    $parts = explode(':', $headerValue, 2);

                    if (2 === count($parts)) {
                        $request = $request->withHeader(trim($parts[0]), trim($parts[1]));
                    }
                }
            }
        }

        if (isset($options['body']) && null !== $this->streamFactory) {
            $bodyContent = '';

            if (is_string($options['body'])) {
                $bodyContent = $options['body'];
            } elseif (is_iterable($options['body'])) {
                foreach ($options['body'] as $chunk) {
                    $bodyContent .= (string) $chunk;
                }
            }
            $request = $request->withBody($this->streamFactory->createStream($bodyContent));
        } elseif (isset($options['json']) && null !== $this->streamFactory) {
            $jsonBody = json_encode($options['json'], JSON_THROW_ON_ERROR);

            if (false !== $jsonBody) {
                $request = $request
                    ->withHeader('Content-Type', 'application/json')
                    ->withBody($this->streamFactory->createStream($jsonBody));
            }
        }

        $response = $this->httpClient->sendRequest($request);

        return $this->getResult($action, $response);
    }

    private function getResult(ActionInterface $action, PsrResponseInterface $response): mixed
    {
        $statusCode = $response->getStatusCode();
        $content = (string) $response->getBody();

        $this->handleResponseStatus($statusCode, $content);

        if ($action instanceof ResponseResultInterface) {
            $factory = $action->getFactory();

            if ($factory instanceof OutputInterface) {
                $data = [];

                try {
                    $decoded = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

                    if (is_array($decoded)) {
                        $data = $decoded;
                    }
                } catch (\Throwable) {
                }

                if (!isset($data['statusCode'])) {
                    $data['statusCode'] = $statusCode;
                }

                return $factory->create($data);
            }
        }

        return $response;
    }
}
