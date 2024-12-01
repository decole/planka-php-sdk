<?php

declare(strict_types=1);

namespace Planka\Bridge\Traits;

use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Planka\Bridge\Views\Factory\Card\CardDtoFactory;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Planka\Bridge\Exceptions\ResponseException;
use Planka\Bridge\Views\Dto\Card\CardDto;

trait CardHydrateTrait
{
    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ResponseException
     * @throws ServerExceptionInterface
     */
    final public function hydrate(ResponseInterface $response): CardDto
    {
        $result = $response->toArray();

        if (array_key_exists('item', $result)) {
            return (new CardDtoFactory())->create($result);
        }

        throw new ResponseException($response->getContent());
    }
}
