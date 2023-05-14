<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\Board;

use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Dto\Board\BoardListDto;
use Planka\Bridge\Traits\DateConverterTrait;

final class BoardListDtoFactory implements OutputInterface
{
    use DateConverterTrait;

    /**
     * @param array{
     *     id: string,
     *     createdAt: string,
     *     updatedAt: ?string,
     *     position: int,
     *     name: string,
     *     boardId: string
     * } $data
     * @return BoardListDto
     */
    public function create(array $data): BoardListDto
    {
        return new BoardListDto(
            id: $data['id'],
            createdAt: $this->convertToDateTime($data['createdAt']),
            updatedAt: $this->convertToDateTime($data['updatedAt']),
            position: (int)$data['position'],
            name: $data['name'],
            boardId: $data['boardId']
        );
    }
}