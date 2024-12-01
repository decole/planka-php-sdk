<?php

declare(strict_types=1);

namespace Planka\Bridge\Traits;

use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Planka\Bridge\Views\Factory\Card\CardMembershipDtoFactory;
use Planka\Bridge\Views\Dto\Card\CardMembershipDto;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Planka\Bridge\Exceptions\ResponseException;

trait CardMembershipHydrateTrait
{
    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ResponseException
     * @throws ServerExceptionInterface
     */
    final public function hydrate(ResponseInterface $response): CardMembershipDto
    {
        $result = $response->toArray();

        if (array_key_exists('item', $result)) {
            return (new CardMembershipDtoFactory())->create($result['item']);
        }

        throw new ResponseException($response->getContent());
    }
}
