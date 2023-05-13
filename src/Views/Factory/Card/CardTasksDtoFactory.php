<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\Card;

use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Dto\Card\CardTasksDto;
use Planka\Bridge\Traits\DateConverterTrait;

final class CardTasksDtoFactory implements OutputInterface
{
    use DateConverterTrait;

    /**
     * @param array{
     *     id: string,
     *     createdAt: string,
     *     updatedAt: ?string,
     *     position: int,
     *     name: string,
     *     isCompleted: bool,
     *     cardId: string
     * } $data
     * @return CardTasksDto
     */
    public function create(array $data): CardTasksDto
    {
        return new CardTasksDto(
            id: $data['id'],
            createdAt: $this->convertToDateTime($data['createdAt']),
            updatedAt: $this->convertToDateTime($data['updatedAt']),
            position: (int)$data['position'],
            name: $data['name'],
            isCompleted: (bool)$data['isCompleted'],
            cardId: $data['cardId']
        );
    }
}