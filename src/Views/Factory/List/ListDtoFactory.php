<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\List;

use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Enum\ListColorEnum;
use Planka\Bridge\Enum\ListTypeEnum;
use Planka\Bridge\Traits\DateConverterTrait;
use Planka\Bridge\Views\Dto\List\ListDto;

final class ListDtoFactory implements OutputInterface
{
    use DateConverterTrait;

    /**
     * @see Payload structure:
     * array{
     *     id: string,
     *     boardId: string,
     *     type?: ?string,
     *     position?: ?int,
     *     name?: ?string,
     *     color?: ?string,
     *     createdAt?: ?string,
     *     updatedAt?: ?string
     * }
     */
    public function create(array $data): ListDto
    {
        $typeEnum = isset($data['type']) && is_string($data['type']) ? ListTypeEnum::tryFrom($data['type']) : null;
        $colorEnum = isset($data['color']) && is_string($data['color']) ? ListColorEnum::tryFrom($data['color']) : null;

        return new ListDto(
            id: (string) $data['id'],
            boardId: (string) ($data['boardId'] ?? ''),
            createdAt: $this->convertToDateTime($data['createdAt'] ?? null) ?? new \DateTimeImmutable(),
            updatedAt: $this->convertToDateTime($data['updatedAt'] ?? null),
            position: (int) ($data['position'] ?? 0),
            name: isset($data['name']) && is_string($data['name']) ? $data['name'] : null,
            type: $typeEnum,
            color: $colorEnum,
            _rawResponse: $data,
        );
    }
}
