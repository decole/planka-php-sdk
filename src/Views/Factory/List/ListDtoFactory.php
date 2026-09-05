<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\List;

use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Traits\DateConverterTrait;
use Planka\Bridge\Views\Dto\List\ListDto;

final class ListDtoFactory implements OutputInterface
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
     */
    public function create(array $data): ListDto
    {
        return new ListDto(
            id: $data['id'],
            boardId: $data['boardId'] ?? '',
            createdAt: $this->convertToDateTime($data['createdAt'] ?? null),
            updatedAt: $this->convertToDateTime($data['updatedAt'] ?? null),
            position: (int) ($data['position'] ?? 0),
            name: $data['name'] ?? null,
            _rawResponse: $data,
        );
    }
}
