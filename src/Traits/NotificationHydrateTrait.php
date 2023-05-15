<?php

declare(strict_types=1);

namespace Planka\Bridge\Traits;

use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Planka\Bridge\Views\Factory\Notification\NotificationItemDtoFactory;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Planka\Bridge\Views\Dto\Notification\NotificationItemDto;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Planka\Bridge\Exceptions\ResponseException;

trait NotificationHydrateTrait
{
    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ResponseException
     * @throws ServerExceptionInterface
     */
    final public function hydrate(ResponseInterface $response): NotificationItemDto
    {
        $result = $response->toArray();

        if (array_key_exists('item', $result)) {
            return (new NotificationItemDtoFactory())->create($result['item']);
        }

        throw new ResponseException($response->getContent());
    }
}