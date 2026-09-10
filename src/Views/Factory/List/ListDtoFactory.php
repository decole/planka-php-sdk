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
        $typeEnum = null;

        if (isset($data['type']) && is_string($data['type'])) {
            $typeEnum = ListTypeEnum::tryFrom($data['type']);
        }

        $colorEnum = null;

        if (isset($data['color']) && is_string($data['color'])) {
            $colorEnum = ListColorEnum::tryFrom($data['color']);
        }

        $name = null;

        if (isset($data['name']) && is_string($data['name'])) {
            $name = $data['name'];
        }

        return new ListDto(
            id: (string) $data['id'],
            boardId: (string) ($data['boardId'] ?? ''),
            createdAt: $this->convertToDateTime($data['createdAt'] ?? null) ?? new \DateTimeImmutable(),
            updatedAt: $this->convertToDateTime($data['updatedAt'] ?? null),
            position: (int) ($data['position'] ?? 0),
            name: $name,
            type: $typeEnum,
            color: $colorEnum,
            _rawResponse: $data,
        );
    }
}
