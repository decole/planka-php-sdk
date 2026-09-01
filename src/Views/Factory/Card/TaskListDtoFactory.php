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
     * @param array{
     *     id: string,
     *     cardId: string,
     *     position: int,
     *     name: string,
     *     showOnFrontOfCard?: bool,
     *     hideCompletedTasks?: bool,
     *     createdAt?: ?string,
     *     updatedAt?: ?string
     * } $data
     */
    public function create(array $data): TaskListDto
    {
        return new TaskListDto(
            id: $data['id'],
            cardId: $data['cardId'],
            position: (int) $data['position'],
            name: $data['name'],
            showOnFrontOfCard: (bool) ($data['showOnFrontOfCard'] ?? true),
            hideCompletedTasks: (bool) ($data['hideCompletedTasks'] ?? false),
            createdAt: $this->convertToDateTime($data['createdAt'] ?? null),
            updatedAt: $this->convertToDateTime($data['updatedAt'] ?? null),
        );
    }
}
