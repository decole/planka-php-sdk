<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\Board;

use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Traits\DateConverterTrait;
use Planka\Bridge\Views\Dto\Board\BoardItemDto;

final class BoardItemDtoFactory implements OutputInterface
{
    use DateConverterTrait;

    /**
     * @param array{
     *     id: string,
     *     createdAt: string,
     *     updatedAt: ?string,
     *     position: int,
     *     name: string,
     *     projectId: string
     * } $data
     * @return BoardItemDto
     */
    public function create(array $data): BoardItemDto
    {
        return new BoardItemDto(
            id: $data['id'],
            projectId: $data['projectId'],
            position: $data['position'],
            name: $data['name'],
            createdAt: $this->convertToDateTime($data['createdAt']),
            updatedAt: $this->convertToDateTime($data['updatedAt'])
        );
    }
}