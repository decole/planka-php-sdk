<?php

declare(strict_types=1);

namespace Planka\Bridge\TransportClients;

use Planka\Bridge\Config;
use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class Client
{
    private HttpClientInterface $client;

    public function __construct(
        private readonly Config $config,
        ?HttpClientInterface $client = null,
    ) {
        $this->client = $client ?? HttpClient::create();
    }

    public function get(ActionInterface $action): mixed
    {
        $response = $this->client->request(
            method: 'GET',
            url: $this->buildUrl($action->url()),
            options: $this->compileOptions($action),
        );

        return $this->getResult($action, $response);
    }

    public function post(ActionInterface $action): mixed
    {
        $response = $this->client->request(
            method: 'POST',
            url: $this->buildUrl($action->url()),
            options: $this->compileOptions($action),
        );

        return $this->getResult($action, $response);
    }

    public function patch(ActionInterface $action): mixed
    {
        $response = $this->client->request(
            method: 'PATCH',
            url: $this->buildUrl($action->url()),
            options: $this->compileOptions($action),
        );

        return $this->getResult($action, $response);
    }

    public function delete(ActionInterface $action): mixed
    {
        $response = $this->client->request(
            method: 'DELETE',
            url: $this->buildUrl($action->url()),
            options: $this->compileOptions($action),
        );

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

    private function getResult(ActionInterface $action, ResponseInterface $response): mixed
    {
        if ($action instanceof ResponseResultInterface) {
            return $action->hydrate($response);
        }

        return $response;
    }

    private function compileOptions(ActionInterface $action): array
    {
        $options = $action->getOptions();

        if (null !== $this->config->getApiKey()) {
            $options['headers']['X-Api-Key'] = $this->config->getApiKey();
        } elseif (null !== $this->config->getAuthToken()) {
            $options['auth_bearer'] = $this->config->getAuthToken();
        } elseif ($action instanceof AuthenticateInterface && null !== $action->getToken()) {
            $options['auth_bearer'] = $action->getToken();
        }

        return $options;
    }
}
