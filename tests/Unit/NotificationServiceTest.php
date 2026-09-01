<?php

declare(strict_types=1);

namespace Planka\Bridge\Tests\Unit;

use Planka\Bridge\Enum\NotificationServiceFormatEnum;
use Planka\Bridge\Views\Dto\NotificationService\NotificationServiceDto;

final class NotificationServiceTest extends AbstractUnitTestCase
{
    public function testCreateNotificationServiceInBoard(): void
    {
        $client = $this->createMockClient('NotificationService/notification_service_create.json');
        $service = $client->notificationService->createInBoard(
            boardId: '1854744330917381674',
            url: 'https://example.com/notif-test',
            format: NotificationServiceFormatEnum::TEXT,
        );

        $this->assertInstanceOf(NotificationServiceDto::class, $service);
        $this->assertNotEmpty($service->id);
    }
}
