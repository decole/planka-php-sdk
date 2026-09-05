<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\Notification;

use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Traits\DateConverterTrait;
use Planka\Bridge\Views\Dto\Notification\NotificationItemDto;

final class NotificationItemDtoFactory implements OutputInterface
{
    use DateConverterTrait;

    public function create(array $data): NotificationItemDto
    {
        return new NotificationItemDto(
            id: $data['id'],
            createdAt: $this->convertToDateTime($data['createdAt'] ?? null),
            updatedAt: $this->convertToDateTime($data['updatedAt'] ?? null),
            isRead: (bool) ($data['isRead'] ?? false),
            userId: $data['userId'] ?? '',
            cardId: $data['cardId'] ?? null,
            actionId: $data['actionId'] ?? null,
            creatorUserId: $data['creatorUserId'] ?? null,
            boardId: $data['boardId'] ?? null,
            commentId: $data['commentId'] ?? null,
            type: $data['type'] ?? null,
            data: is_array($data['data'] ?? null) ? $data['data'] : [],
            _rawResponse: $data,
        );
    }
}
