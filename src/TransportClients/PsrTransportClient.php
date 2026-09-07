<?php

declare(strict_types=1);

namespace Planka\Bridge\TransportClients;

use Planka\Bridge\Config;
use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Exceptions\PlankaAccessDeniedException;
use Planka\Bridge\Exceptions\PlankaNotFoundException;
use Planka\Bridge\Exceptions\PlankaServerException;
use Planka\Bridge\Exceptions\PlankaValidationException;
use Planka\Bridge\Exceptions\ResponseException;
use Psr\Http\Client\ClientInterface as PsrClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;

final class PsrTransportClient implements TransportClientInterface
{
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

    private function send(string $method, ActionInterface $action): mixed
    {
        $url = $this->buildUrl($action->url());
        $request = $this->requestFactory->createRequest($method, $url);

        $options = $action->getOptions();

        if ($action instanceof AuthenticateInterface) {
            if (null !== $this->config->getApiKey()) {
                $request = $request->withHeader('X-Api-Key', $this->config->getApiKey());
            } elseif (null !== $this->config->getAuthToken()) {
                $request = $request->withHeader('Authorization', 'Bearer ' . $this->config->getAuthToken());
            }
        }

        if (isset($options['headers']) && is_array($options['headers'])) {
            foreach ($options['headers'] as $headerName => $headerValue) {
                if (is_string($headerName) && is_string($headerValue)) {
                    $request = $request->withHeader($headerName, $headerValue);
                }
            }
        }

        if (isset($options['json']) && null !== $this->streamFactory) {
            $jsonBody = json_encode($options['json']);

            if (false !== $jsonBody) {
                $request = $request
                    ->withHeader('Content-Type', 'application/json')
                    ->withBody($this->streamFactory->createStream($jsonBody));
            }
        }

        $response = $this->httpClient->sendRequest($request);

        return $this->getResult($action, $response);
    }

    private function buildUrl(string $path): string
    {
        $base = rtrim($this->config->getBaseUri(), '/');

        if (
            80 !== $this->config->getPort()
            && 443 !== $this->config->getPort()
            && false === strpos($base, ':', 7)
        ) {
            $base .= ':' . $this->config->getPort();
        }

        return $base . '/' . ltrim($path, '/');
    }

    private function getResult(ActionInterface $action, PsrResponseInterface $response): mixed
    {
        $statusCode = $response->getStatusCode();
        $content = (string) $response->getBody();

        if ($statusCode < 200 || $statusCode >= 300) {
            match (true) {
                404 === $statusCode => throw new PlankaNotFoundException($content, 404),
                400 === $statusCode, 422 === $statusCode => throw new PlankaValidationException($content, $statusCode),
                401 === $statusCode, 403 === $statusCode => throw new PlankaAccessDeniedException($content, $statusCode),
                $statusCode >= 500 => throw new PlankaServerException($content, $statusCode),
                default => throw new ResponseException($content, $statusCode),
            };
        }

        if ($action instanceof ResponseResultInterface) {
            $factory = $action->getFactory();

            if ($factory instanceof OutputInterface) {
                $data = [];

                try {
                    $decoded = json_decode($content, true);

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
