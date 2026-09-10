<?php

declare(strict_types=1);

namespace Planka\Bridge\Controllers;

use Planka\Bridge\Actions\Common\CommonPatchAction;
use Planka\Bridge\Actions\Notification\NotificationListAction;
use Planka\Bridge\Actions\Notification\NotificationReadAllAction;
use Planka\Bridge\Actions\Notification\NotificationUpdateAction;
use Planka\Bridge\Actions\Notification\NotificationVewAction;
use Planka\Bridge\Contracts\Resources\NotificationResourceInterface;
use Planka\Bridge\TransportClients\TransportClientInterface;
use Planka\Bridge\Views\Dto\Notification\NotificationItemDto;
use Planka\Bridge\Views\Dto\Notification\NotificationListDto;
use Planka\Bridge\Views\Factory\Notification\NotificationItemDtoFactory;

final class Notification implements NotificationResourceInterface
{
    public function __construct(private readonly TransportClientInterface $client) {}

    /** 'GET /api/notifications' */
    public function list(): NotificationListDto
    {
        return $this->client->get(new NotificationListAction());
    }

    /**
     * 'GET /api/notifications/:id'.
     */
    public function getOne(string $notifyId): NotificationItemDto
    {
        return $this->client->get(new NotificationVewAction(
            notificationId: $notifyId,
        ));
    }

    /**
     * 'PATCH /api/notifications/:id'.
     *
     * @return list<NotificationItemDto>
     */
    public function markIsRead(string $notifyId): array
    {
        return $this->client->patch(new NotificationUpdateAction(
            notificationId: $notifyId,
            isRead: true,
        ));
    }

    /**
     * 'PATCH /api/notifications/:id'.
     *
     * @return list<NotificationItemDto>
     */
    public function markIsNotRead(string $notifyId): array
    {
        return $this->client->patch(new NotificationUpdateAction(
            notificationId: $notifyId,
            isRead: false,
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
            hydrateCallback: new NotificationItemDtoFactory(),
        ));
    }

    /** 'POST /api/notifications/read-all' */
    public function readAll(): NotificationListDto
    {
        return $this->client->post(new NotificationReadAllAction());
    }
}
