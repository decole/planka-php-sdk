<?php

namespace Planka\Bridge\Traits;

use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Planka\Bridge\Views\Factory\User\UserDtoFactory;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Planka\Bridge\Exceptions\ResponseException;
use Planka\Bridge\Views\Dto\User\UserDto;
use function Fp\Collection\map;

trait UserHydrateTrait
{
    /**
     * @return list<UserDto>|UserDto
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ResponseException
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    final public function hydrate(ResponseInterface $response): array|UserDto
    {
        $result = $response->toArray();

        if (array_key_exists('item', $result)) {
            return (new UserDtoFactory())->create($response->toArray()['item']);
        }

        if (array_key_exists('items', $result)) {
            return map($result['items'], fn(array $item) => (new UserDtoFactory())->create($item));
        }

        throw new ResponseException($response->getContent());
    }
}