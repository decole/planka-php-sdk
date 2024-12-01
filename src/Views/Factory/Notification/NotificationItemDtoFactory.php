<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\Notification;

use Planka\Bridge\Views\Dto\Notification\NotificationItemDto;
use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Traits\DateConverterTrait;

final class NotificationItemDtoFactory implements OutputInterface
{
    use DateConverterTrait;

    /**
     * @param array{
     *     id: string,
     *     createdAt: string,
     *     updatedAt: ?string,
     *     isRead: bool,
     *     userId: string,
     *     actionId: string,
     *     cardId: string
     * } $data
     */
    public function create(array $data): NotificationItemDto
    {
        return new NotificationItemDto(
            id: $data['id'],
            createdAt: $this->convertToDateTime($data['createdAt']),
            updatedAt: $this->convertToDateTime($data['updatedAt']),
            isRead: (bool) $data['isRead'],
            userId: $data['userId'],
            cardId: $data['cardId'],
            actionId: $data['actionId'],
        );
    }
}
