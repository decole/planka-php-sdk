<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\Card;

use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Traits\DateConverterTrait;
use Planka\Bridge\Views\Dto\Card\CardTaskDto;

final class CardTaskDtoFactory implements OutputInterface
{
    use DateConverterTrait;

    /**
     * @param array<string, mixed> $data
     */
    public function create(array $data): CardTaskDto
    {
        $data = $data['item'] ?? $data;

        return new CardTaskDto(
            id: (string) $data['id'],
            taskListId: (string) $data['taskListId'],
            linkedCardId: isset($data['linkedCardId']) && is_string($data['linkedCardId']) ? $data['linkedCardId'] : null,
            assigneeUserId: isset($data['assigneeUserId']) && is_string($data['assigneeUserId']) ? $data['assigneeUserId'] : null,
            position: (int) ($data['position'] ?? 0),
            name: (string) ($data['name'] ?? ''),
            isCompleted: (bool) ($data['isCompleted'] ?? false),
            createdAt: $this->convertToDateTime($data['createdAt'] ?? null),
            updatedAt: $this->convertToDateTime($data['updatedAt'] ?? null),
            _rawResponse: $data,
        );
    }
}
