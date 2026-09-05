<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\Board;

use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Enum\ListColorEnum;
use Planka\Bridge\Enum\ListTypeEnum;
use Planka\Bridge\Traits\DateConverterTrait;
use Planka\Bridge\Views\Dto\Board\BoardListDto;

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
     *     boardId: string,
     *     type?: ?string,
     *     color?: ?string
     * } $data
     */
    public function create(array $data): BoardListDto
    {
        $typeEnum = isset($data['type']) && is_string($data['type']) ? ListTypeEnum::tryFrom($data['type']) : null;
        $colorEnum = isset($data['color']) && is_string($data['color']) ? ListColorEnum::tryFrom($data['color']) : null;

        return new BoardListDto(
            id: $data['id'],
            createdAt: $this->convertToDateTime($data['createdAt']),
            updatedAt: $this->convertToDateTime($data['updatedAt']),
            position: (int) $data['position'],
            name: $data['name'],
            boardId: $data['boardId'],
            type: $typeEnum,
            color: $colorEnum,
            _rawResponse: $data,
        );
    }
}
