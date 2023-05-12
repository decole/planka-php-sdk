<?php

declare(strict_types=1);

namespace Planka\Bridge\Traits;

use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Planka\Bridge\Views\Factory\Board\BoardDtoFactory;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Planka\Bridge\Exceptions\ResponseException;
use Planka\Bridge\Views\Dto\Board\BoardDto;

trait BoardHydrateTrait
{
    /**
     * @return BoardDto
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ResponseException
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    final public function hydrate(ResponseInterface $response): BoardDto
    {
        $result = $response->toArray();

        if (array_key_exists('item', $result)) {
            return (new BoardDtoFactory())->create($response->toArray());
        }

        throw new ResponseException($response->getContent());
    }
}