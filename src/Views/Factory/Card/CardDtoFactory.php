<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\Card;

use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Traits\DateConverterTrait;
use Planka\Bridge\Views\Dto\Card\CardDto;

final class CardDtoFactory implements OutputInterface
{
    use DateConverterTrait;

    /**
     * @param array{
     *     id: string,
     *     createdAt: string,
     *     updatedAt: ?string,
     *     position: int,
     *     name: string,
     *     description: ?string,
     *     dueDate: ?string,
     *     stopwatch: array,
     *     boardId: string,
     *     listId: ?string,
     *     creatorUserId: string,
     *     coverAttachmentId: ?string,
     *     isSubscribed: ?bool
     * } $data
     * @return CardDto
     */
    public function create(array $data): CardDto
    {
        return new CardDto(
            id: $data['id'],
            createdAt: $this->convertToDateTime($data['createdAt']),
            updatedAt: $this->convertToDateTime($data['updatedAt']),
            creatorUserId: $data['creatorUserId'],
            position: $data['position'],
            name: $data['name'],
            description: $data['description'],
            dueDate: $this->convertToDateTime($data['dueDate']),
            stopwatch: (new StopWatchDtoFactory())->create($data['stopwatch']),
            boardId: $data['boardId'],
            listId: $data['listId'],
            coverAttachmentId: $data['coverAttachmentId'],
            isSubscribed: (bool)($data['isSubscribed'] ?? false)
        );
    }
}