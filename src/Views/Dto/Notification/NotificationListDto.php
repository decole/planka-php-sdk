<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\Notification;

class NotificationListDto
{
    /**
     * @param list<NotificationItemDto> $items
     */
    public function __construct(
        public readonly array $items,
        public readonly NotificationIncludedDto $included,
        /** @var array<string, mixed> Diagnostic raw response array from Planka API to verify DTO field hydration. */
        public readonly array $_rawResponse = [],
    ) {}
}
