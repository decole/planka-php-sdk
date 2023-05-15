<?php

declare(strict_types=1);

namespace Planka\Bridge\Controllers;

use Planka\Bridge\Actions\Notification\NotificationUpdateAction;
use Planka\Bridge\Actions\Notification\NotificationListAction;
use Planka\Bridge\Actions\Notification\NotificationVewAction;
use Planka\Bridge\Views\Dto\Notification\NotificationItemDto;
use Planka\Bridge\Views\Dto\Notification\NotificationListDto;
use Planka\Bridge\TransportClients\Client;
use Planka\Bridge\Config;

final class Notification
{
    public function __construct(
        private readonly Config $config,
        private readonly Client $client
    ) {
    }

    /** 'GET /api/notifications' */
    public function list(): NotificationListDto
    {
        return $this->client->get(new NotificationListAction(token: $this->config->getAuthToken()));
    }

    /**
     * 'GET /api/notifications/:id'
     */
    public function getOne(string $notifyId): NotificationItemDto
    {
        return $this->client->get(new NotificationVewAction(
            notifyId: $notifyId,
            token: $this->config->getAuthToken()
        ));
    }

    /**
     * 'PATCH /api/notifications/:ids'
     * @return list<NotificationItemDto>
     */
    public function markIsRead(array $notifyIdList): array
    {
        return $this->client->patch(new NotificationUpdateAction(
            notifyIdList: $notifyIdList,
            isRead: true,
            token: $this->config->getAuthToken()
        ));
    }

    /**
     * 'PATCH /api/notifications/:ids'
     * @return list<NotificationItemDto>
     */
    public function markIsNotRead(array $notifyIdList): array
    {
        return $this->client->patch(new NotificationUpdateAction(
            notifyIdList: $notifyIdList,
            isRead: false,
            token: $this->config->getAuthToken()
        ));
    }
}