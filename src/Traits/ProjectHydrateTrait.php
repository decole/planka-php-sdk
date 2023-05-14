<?php

namespace Planka\Bridge\Traits;

use Planka\Bridge\Exceptions\ResponseException;
use Planka\Bridge\Views\Dto\Card\CardDto;
use Planka\Bridge\Views\Dto\Project\ProjectDto;
use Planka\Bridge\Views\Factory\Card\CardDtoFactory;
use Planka\Bridge\Views\Factory\Project\ProjectDtoFactory;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

trait ProjectHydrateTrait
{
    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ResponseException
     * @throws ServerExceptionInterface
     */
    final public function hydrate(ResponseInterface $response): ProjectDto
    {
        $result = $response->toArray();

        if (array_key_exists('item', $result)) {
            return (new ProjectDtoFactory())->create($result['item']);
        }

        throw new ResponseException($response->getContent());
    }
}