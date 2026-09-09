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
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class Client implements TransportClientInterface
{
    use TransportClientTrait;

    private HttpClientInterface $client;

    public function __construct(
        private readonly Config $config,
        ?HttpClientInterface $client = null,
    ) {
        $this->client = $client ?? HttpClient::create();
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ResponseException
     * @throws PlankaNotFoundException
     * @throws PlankaValidationException
     * @throws PlankaAccessDeniedException
     * @throws PlankaServerException
     */
    public function get(ActionInterface $action): mixed
    {
        $response = $this->client->request(
            method: 'GET',
            url: $this->buildUrl($this->config, $action->url()),
            options: $this->compileOptions($action),
        );

        return $this->getResult($action, $response);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ResponseException
     * @throws PlankaNotFoundException
     * @throws PlankaValidationException
     * @throws PlankaAccessDeniedException
     * @throws PlankaServerException
     */
    public function post(ActionInterface $action): mixed
    {
        $response = $this->client->request(
            method: 'POST',
            url: $this->buildUrl($this->config, $action->url()),
            options: $this->compileOptions($action),
        );

        return $this->getResult($action, $response);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ResponseException
     * @throws PlankaNotFoundException
     * @throws PlankaValidationException
     * @throws PlankaAccessDeniedException
     * @throws PlankaServerException
     */
    public function patch(ActionInterface $action): mixed
    {
        $response = $this->client->request(
            method: 'PATCH',
            url: $this->buildUrl($this->config, $action->url()),
            options: $this->compileOptions($action),
        );

        return $this->getResult($action, $response);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ResponseException
     * @throws PlankaNotFoundException
     * @throws PlankaValidationException
     * @throws PlankaAccessDeniedException
     * @throws PlankaServerException
     */
    public function delete(ActionInterface $action): mixed
    {
        $response = $this->client->request(
            method: 'DELETE',
            url: $this->buildUrl($this->config, $action->url()),
            options: $this->compileOptions($action),
        );

        return $this->getResult($action, $response);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ResponseException
     * @throws PlankaNotFoundException
     * @throws PlankaAccessDeniedException
     * @throws PlankaServerException
     * @throws PlankaValidationException
     */
    private function getResult(ActionInterface $action, ResponseInterface $response): mixed
    {
        $this->checkResponseStatusCode($response);

        if ($action instanceof ResponseResultInterface) {
            $factory = $action->getFactory();

            if ($factory instanceof OutputInterface) {
                $data = [];

                try {
                    $data = $response->toArray();
                } catch (\Throwable) {
                }

                if (!isset($data['statusCode'])) {
                    $data['statusCode'] = $response->getStatusCode();
                }

                return $factory->create($data);
            }

            if (\is_callable($factory)) {
                return $factory($response);
            }
        }

        return $response;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ResponseException
     * @throws PlankaNotFoundException
     * @throws PlankaAccessDeniedException
     * @throws PlankaServerException
     * @throws PlankaValidationException
     */
    private function checkResponseStatusCode(ResponseInterface $response): void
    {
        $content = '';

        try {
            $content = $response->getContent(false);
        } catch (\Throwable) {
        }

        $this->handleResponseStatus($response->getStatusCode(), $content);
    }

    private function compileOptions(ActionInterface $action): array
    {
        $options = $action->getOptions();

        if ($action instanceof AuthenticateInterface) {
            if (null !== $this->config->getApiKey()) {
                $options['headers']['X-Api-Key'] = $this->config->getApiKey();
            } elseif (null !== $this->config->getAuthToken()) {
                $options['auth_bearer'] = $this->config->getAuthToken();
            }
        }

        return $options;
    }
}
