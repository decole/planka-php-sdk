<?php

declare(strict_types=1);

namespace Planka\Bridge\Controllers;

use Planka\Bridge\Actions\NotificationService\NotificationServiceCreateInBoardAction;
use Planka\Bridge\Actions\NotificationService\NotificationServiceCreateInUserAction;
use Planka\Bridge\Actions\NotificationService\NotificationServiceDeleteAction;
use Planka\Bridge\Actions\NotificationService\NotificationServiceTestAction;
use Planka\Bridge\Actions\NotificationService\NotificationServiceUpdateAction;
use Planka\Bridge\Config;
use Planka\Bridge\Enum\NotificationServiceFormatEnum;
use Planka\Bridge\TransportClients\Client;
use Planka\Bridge\Views\Dto\NotificationService\NotificationServiceDto;

final class NotificationService
{
    public function __construct(
        private readonly Config $config,
        private readonly Client $client,
    ) {}

    /** 'POST /api/boards/:boardId/notification-services' */
    public function createInBoard(string $boardId, string $url, NotificationServiceFormatEnum $format): NotificationServiceDto
    {
        return $this->client->post(new NotificationServiceCreateInBoardAction(
            boardId: $boardId,
            url: $url,
            format: $format,
        ));
    }

    /** 'POST /api/users/:userId/notification-services' */
    public function createInUser(string $userId, string $url, NotificationServiceFormatEnum $format): NotificationServiceDto
    {
        return $this->client->post(new NotificationServiceCreateInUserAction(
            userId: $userId,
            url: $url,
            format: $format,
        ));
    }

    /** 'PATCH /api/notification-services/:id' */
    public function update(string $id, ?string $url = null, ?NotificationServiceFormatEnum $format = null): NotificationServiceDto
    {
        return $this->client->patch(new NotificationServiceUpdateAction(
            id: $id,
            url: $url,
            format: $format,
        ));
    }

    /** 'DELETE /api/notification-services/:id' */
    public function delete(string $id): NotificationServiceDto
    {
        return $this->client->delete(new NotificationServiceDeleteAction(id: $id));
    }

    /** 'POST /api/notification-services/:id/test' */
    public function test(string $id): array
    {
        return $this->client->post(new NotificationServiceTestAction(id: $id));
    }
}
