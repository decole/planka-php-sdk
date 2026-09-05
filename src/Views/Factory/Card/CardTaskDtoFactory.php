<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\Card;

use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Traits\DateConverterTrait;
use Planka\Bridge\Views\Dto\Card\CardTaskDto;

final class CardTaskDtoFactory implements OutputInterface
{
    use DateConverterTrait;

    public function create(array $data): CardTaskDto
    {
        return new CardTaskDto(
            id: $data['id'],
            taskListId: $data['taskListId'],
            linkedCardId: $data['linkedCardId'] ?? null,
            assigneeUserId: $data['assigneeUserId'] ?? null,
            position: (int) $data['position'],
            name: $data['name'],
            isCompleted: (bool) ($data['isCompleted'] ?? false),
            createdAt: $this->convertToDateTime($data['createdAt'] ?? null),
            updatedAt: $this->convertToDateTime($data['updatedAt'] ?? null),
            _rawResponse: $data,
        );
    }
}
