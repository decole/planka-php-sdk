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
     * @param array<string, mixed> $data
     *
     * @see Payload structure:
     * array{
     *     id: string,
     *     createdAt: string,
     *     updatedAt: ?string,
     *     position: int,
     *     name: string,
     *     boardId: string,
     *     type?: ?string,
     *     color?: ?string
     * }
     */
    public function create(array $data): BoardListDto
    {
        $data = $data['item'] ?? $data;
        $typeEnum = isset($data['type']) && is_string($data['type']) ? ListTypeEnum::tryFrom($data['type']) : null;
        $colorEnum = isset($data['color']) && is_string($data['color']) ? ListColorEnum::tryFrom($data['color']) : null;

        return new BoardListDto(
            id: (string) $data['id'],
            createdAt: $this->convertToDateTime($data['createdAt'] ?? null) ?? new \DateTimeImmutable(),
            updatedAt: $this->convertToDateTime($data['updatedAt'] ?? null),
            position: (int) ($data['position'] ?? 0),
            name: (string) ($data['name'] ?? ''),
            boardId: (string) ($data['boardId'] ?? ''),
            type: $typeEnum,
            color: $colorEnum,
            _rawResponse: $data,
        );
    }
}
