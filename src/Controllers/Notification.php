<?php

declare(strict_types=1);

namespace Planka\Bridge\Controllers;

use Planka\Bridge\Actions\Common\CommonPatchAction;
use Planka\Bridge\Actions\Notification\NotificationListAction;
use Planka\Bridge\Actions\Notification\NotificationUpdateAction;
use Planka\Bridge\Actions\Notification\NotificationVewAction;
use Planka\Bridge\Config;
use Planka\Bridge\Traits\NotificationHydrateTrait;
use Planka\Bridge\TransportClients\Client;
use Planka\Bridge\Views\Dto\Notification\NotificationItemDto;
use Planka\Bridge\Views\Dto\Notification\NotificationListDto;

final class Notification
{
    use NotificationHydrateTrait;

    public function __construct(
        private readonly Config $config,
        private readonly Client $client,
    ) {}

    /** 'GET /api/notifications' */
    public function list(): NotificationListDto
    {
        return $this->client->get(new NotificationListAction(token: $this->config->getAuthToken()));
    }

    /**
     * 'GET /api/notifications/:id'.
     */
    public function getOne(string $notifyId): NotificationItemDto
    {
        return $this->client->get(new NotificationVewAction(
            notifyId: $notifyId,
            token: $this->config->getAuthToken(),
        ));
    }

    /**
     * 'PATCH /api/notifications/:ids'.
     *
     * @return list<NotificationItemDto>
     */
    public function markIsRead(array $notifyIdList): array
    {
        return $this->client->patch(new NotificationUpdateAction(
            notifyIdList: $notifyIdList,
            isRead: true,
            token: $this->config->getAuthToken(),
        ));
    }

    /**
     * 'PATCH /api/notifications/:ids'.
     *
     * @return list<NotificationItemDto>
     */
    public function markIsNotRead(array $notifyIdList): array
    {
        return $this->client->patch(new NotificationUpdateAction(
            notifyIdList: $notifyIdList,
            isRead: false,
            token: $this->config->getAuthToken(),
        ));
    }

    /**
     * 'PATCH /api/notifications/:id' - Partially updates notification properties.
     *
     * @param string $notifyId Notification ID
     * @param array{
     *   isRead?: bool
     * } $map Associative array of fields to update
     */
    public function patching(string $notifyId, array $map): NotificationItemDto
    {
        return $this->client->patch(new CommonPatchAction(
            urlPath: "api/notifications/{$notifyId}",
            data: $map,
            hydrateCallback: fn($response) => $this->hydrate($response),
        ));
    }

    /** 'POST /api/notifications/read-all' */
    public function readAll(): array
    {
        return $this->client->post(new \Planka\Bridge\Actions\Notification\NotificationReadAllAction());
    }
}
