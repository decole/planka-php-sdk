<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\Board;

use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Dto\Board\BoardItemDto;
use Planka\Bridge\Traits\DateConverterTrait;

final class BoardItemDtoFactory implements OutputInterface
{
    use DateConverterTrait;

    /**
     * @param array{
     *     id: string,
     *     createdAt: string,
     *     updatedAt: ?string,
     *     position: float,
     *     name: string,
     *     projectId: string
     * } $data
     */
    public function create(array $data): BoardItemDto
    {
        return new BoardItemDto(
            id: $data['id'],
            projectId: $data['projectId'],
            position: (int) $data['position'],
            name: $data['name'],
            createdAt: $this->convertToDateTime($data['createdAt']),
            updatedAt: $this->convertToDateTime($data['updatedAt']),
        );
    }
}
