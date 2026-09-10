<?php

declare(strict_types=1);

namespace Planka\Bridge\Contracts\Resources;

use Planka\Bridge\Views\Dto\Notification\NotificationItemDto;
use Planka\Bridge\Views\Dto\Notification\NotificationListDto;

interface NotificationResourceInterface
{
    public function list(): NotificationListDto;

    public function getOne(string $notifyId): NotificationItemDto;

    /**
     * @return list<NotificationItemDto>
     */
    public function markIsRead(string $notifyId): array;

    /**
     * @return list<NotificationItemDto>
     */
    public function markIsNotRead(string $notifyId): array;

    /**
     * @param array{isRead?: bool} $map
     */
    public function patching(string $notifyId, array $map): NotificationItemDto;

    public function readAll(): NotificationListDto;
}
