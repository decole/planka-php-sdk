<?php

declare(strict_types=1);

namespace Planka\Bridge\Controllers;

use Planka\Bridge\Actions\Common\CommonPatchAction;
use Planka\Bridge\Actions\NotificationService\NotificationServiceCreateInBoardAction;
use Planka\Bridge\Actions\NotificationService\NotificationServiceCreateInUserAction;
use Planka\Bridge\Actions\NotificationService\NotificationServiceDeleteAction;
use Planka\Bridge\Actions\NotificationService\NotificationServiceTestAction;
use Planka\Bridge\Actions\NotificationService\NotificationServiceUpdateAction;
use Planka\Bridge\Enum\NotificationServiceFormatEnum;
use Planka\Bridge\TransportClients\TransportClientInterface;
use Planka\Bridge\Views\Dto\Common\TestResultDto;
use Planka\Bridge\Views\Dto\NotificationService\NotificationServiceDto;
use Planka\Bridge\Views\Factory\NotificationService\NotificationServiceDtoFactory;

final class NotificationService
{
    public function __construct(private readonly TransportClientInterface $client) {}

    /** 'POST /api/boards/:boardId/notification-services' */
    public function createInBoard(
        string $boardId,
        string $url,
        NotificationServiceFormatEnum $format,
    ): NotificationServiceDto {
        return $this->client->post(new NotificationServiceCreateInBoardAction(
            boardId: $boardId,
            url: $url,
            format: $format,
        ));
    }

    /** 'POST /api/users/:userId/notification-services' */
    public function createInUser(
        string $userId,
        string $url,
        NotificationServiceFormatEnum $format,
    ): NotificationServiceDto {
        return $this->client->post(new NotificationServiceCreateInUserAction(
            userId: $userId,
            url: $url,
            format: $format,
        ));
    }

    /** 'PATCH /api/notification-services/:id' */
    public function update(
        string $id,
        ?string $url = null,
        ?NotificationServiceFormatEnum $format = null,
    ): NotificationServiceDto {
        $data = [];

        if (null !== $url) {
            $data['url'] = $url;
        }

        if (null !== $format) {
            $data['format'] = $format->value;
        }

        return $this->client->patch(new NotificationServiceUpdateAction(
            serviceId: $id,
            data: $data,
        ));
    }

    /**
     * 'PATCH /api/notification-services/:id' - Partially updates notification service properties.
     *
     * @param string $id Notification service ID
     * @param array{
     *   url?: string,
     *   format?: 'text'|'markdown'|'html'|NotificationServiceFormatEnum
     * } $map Associative array of fields to update
     */
    public function patching(string $id, array $map): NotificationServiceDto
    {
        if (isset($map['format']) && $map['format'] instanceof NotificationServiceFormatEnum) {
            $map['format'] = $map['format']->value;
        }

        return $this->client->patch(new CommonPatchAction(
            urlPath: "api/notification-services/{$id}",
            data: $map,
            hydrateCallback: new NotificationServiceDtoFactory(),
        ));
    }

    /** 'DELETE /api/notification-services/:id' */
    public function delete(string $id): NotificationServiceDto
    {
        return $this->client->delete(new NotificationServiceDeleteAction(serviceId: $id));
    }

    /** 'POST /api/notification-services/:id/test' */
    public function test(string $id): TestResultDto
    {
        return $this->client->post(new NotificationServiceTestAction(serviceId: $id));
    }
}
