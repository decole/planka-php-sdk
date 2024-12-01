<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\Notification;

class NotificationIncludedDto
{
    public function __construct(
        public readonly array $users,
        public readonly array $cards,
        public readonly array $actions,
    ) {}
}
