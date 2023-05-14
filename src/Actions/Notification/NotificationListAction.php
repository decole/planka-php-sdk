<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\Notification;

use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Planka\Bridge\Views\Factory\Notification\NotificationListDtoFactory;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Planka\Bridge\Views\Dto\Notification\NotificationListDto;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ActionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Planka\Bridge\Traits\AuthenticateTrait;

final class NotificationListAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    use AuthenticateTrait;

    public function __construct(
        string $token,
    ) {
        $this->setToken($token);
    }

    public function url(): string
    {
        return "api/notifications";
    }

    public function getOptions(): array
    {
        return [];
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function hydrate(ResponseInterface $response): NotificationListDto
    {
        return (new NotificationListDtoFactory())->create($response->toArray());
    }
}