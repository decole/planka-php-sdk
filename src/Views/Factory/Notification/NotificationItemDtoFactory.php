<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\Notification;

use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Traits\DateConverterTrait;
use Planka\Bridge\Views\Dto\Notification\NotificationItemDto;

final class NotificationItemDtoFactory implements OutputInterface
{
    use DateConverterTrait;

    /**
     * @param array<string, mixed> $data
     *
     * @see Payload structure:
     * array{
     *     id: string,
     *     createdAt?: ?string,
     *     updatedAt?: ?string,
     *     isRead?: bool,
     *     userId?: string,
     *     cardId?: ?string,
     *     actionId?: ?string,
     *     creatorUserId?: ?string,
     *     boardId?: ?string,
     *     commentId?: ?string,
     *     type?: ?string,
     *     data?: array
     * }
     */
    public function create(array $data): NotificationItemDto
    {
        $item = $data['item'] ?? $data;

        return new NotificationItemDto(
            id: (string) $item['id'],
            createdAt: $this->convertToDateTime($item['createdAt'] ?? null) ?? new \DateTimeImmutable(),
            updatedAt: $this->convertToDateTime($item['updatedAt'] ?? null),
            isRead: (bool) ($item['isRead'] ?? false),
            userId: (string) ($item['userId'] ?? ''),
            cardId: isset($item['cardId']) && is_string($item['cardId']) ? $item['cardId'] : null,
            actionId: isset($item['actionId']) && is_string($item['actionId']) ? $item['actionId'] : null,
            creatorUserId: isset($item['creatorUserId']) && is_string($item['creatorUserId']) ? $item['creatorUserId'] : null,
            boardId: isset($item['boardId']) && is_string($item['boardId']) ? $item['boardId'] : null,
            commentId: isset($item['commentId']) && is_string($item['commentId']) ? $item['commentId'] : null,
            type: isset($item['type']) && is_string($item['type']) ? $item['type'] : null,
            data: is_array($item['data'] ?? null) ? $item['data'] : [],
            _rawResponse: $data,
        );
    }
}
