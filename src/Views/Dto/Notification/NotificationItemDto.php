<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\Notification;

use Planka\Bridge\Contracts\Dto\OutputDtoInterface;

class NotificationItemDto implements OutputDtoInterface
{
    public function __construct(
        public readonly string $id,
        public readonly \DateTimeImmutable $createdAt,
        public readonly ?\DateTimeImmutable $updatedAt,
        public readonly bool $isRead,
        public readonly string $userId,
        public readonly ?string $cardId = null,
        public readonly ?string $actionId = null,
        public readonly ?string $creatorUserId = null,
        public readonly ?string $boardId = null,
        public readonly ?string $commentId = null,
        public readonly ?string $type = null,
        public readonly array $data = [],
        public readonly array $_rawResponse = [],
    ) {}
}
