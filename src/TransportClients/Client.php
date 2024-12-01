<?php

declare(strict_types=1);

namespace Planka\Bridge\TransportClients;

use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Planka\Bridge\Contracts\Actions\ActionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Component\HttpClient\HttpClient;

class Client
{
    private const URI_TEMPLATE = '%s:%u/%s';

    private HttpClientInterface $client;

    public function __construct(
        private readonly string $baseUri,
        private readonly int $port,
        ?HttpClientInterface $client = null,
    ) {
        $this->client = $client ?? HttpClient::create();
    }

    /**
     * check throw to auth, and return authNotAuth exception.
     */
    public function get(ActionInterface $action): mixed
    {
        $response = $this->client->request(
            method: 'GET',
            url: sprintf(self::URI_TEMPLATE, $this->baseUri, $this->port, $action->url()),
            options: $this->compileOptions($action),
        );

        return $this->getResult($action, $response);
    }

    public function post(ActionInterface $action): mixed
    {
        $response = $this->client->request(
            method: 'POST',
            url: sprintf(self::URI_TEMPLATE, $this->baseUri, $this->port, $action->url()),
            options: $this->compileOptions($action),
        );

        return $this->getResult($action, $response);
    }

    public function patch(ActionInterface $action): mixed
    {
        $response = $this->client->request(
            method: 'PATCH',
            url: sprintf(self::URI_TEMPLATE, $this->baseUri, $this->port, $action->url()),
            options: $this->compileOptions($action),
        );

        return $this->getResult($action, $response);
    }

    public function delete(ActionInterface $action): mixed
    {
        $response = $this->client->request(
            method: 'DELETE',
            url: sprintf(self::URI_TEMPLATE, $this->baseUri, $this->port, $action->url()),
            options: $this->compileOptions($action),
        );

        return $this->getResult($action, $response);
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

        if ($action instanceof AuthenticateInterface) {
            $options['auth_bearer'] = $action->getToken();
        }

        return $options;
    }
}
