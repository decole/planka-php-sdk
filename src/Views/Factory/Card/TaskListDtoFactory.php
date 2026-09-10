<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\Card;

use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Traits\DateConverterTrait;
use Planka\Bridge\Views\Dto\Card\TaskListDto;

final class TaskListDtoFactory implements OutputInterface
{
    use DateConverterTrait;

    /**
     * @param array<string, mixed> $data
     *
     * @see Payload structure:
     * array{
     *     id: string,
     *     cardId: string,
     *     position: int,
     *     name: string,
     *     showOnFrontOfCard?: bool,
     *     hideCompletedTasks?: bool,
     *     createdAt?: ?string,
     *     updatedAt?: ?string
     * }
     */
    public function create(array $data): TaskListDto
    {
        $data = $data['item'] ?? $data;

        return new TaskListDto(
            id: (string) $data['id'],
            cardId: (string) $data['cardId'],
            position: (int) ($data['position'] ?? 0),
            name: (string) ($data['name'] ?? ''),
            showOnFrontOfCard: (bool) ($data['showOnFrontOfCard'] ?? true),
            hideCompletedTasks: (bool) ($data['hideCompletedTasks'] ?? false),
            createdAt: $this->convertToDateTime($data['createdAt'] ?? null),
            updatedAt: $this->convertToDateTime($data['updatedAt'] ?? null),
            _rawResponse: $data,
        );
    }
}
