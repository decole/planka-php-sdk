<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\Notification;

class NotificationListDto
{
    /**
     * @param list<NotificationItemDto> $items
     * @param NotificationIncludedDto $included
     */
    public function __construct(
        public readonly array $items,
        public readonly NotificationIncludedDto $included,
    ) {
    }
}