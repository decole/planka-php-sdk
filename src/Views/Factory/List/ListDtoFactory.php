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

    public function create(array $data): ListDto
    {
        $typeEnum = isset($data['type']) && is_string($data['type']) ? ListTypeEnum::tryFrom($data['type']) : null;
        $colorEnum = isset($data['color']) && is_string($data['color']) ? ListColorEnum::tryFrom($data['color']) : null;

        return new ListDto(
            id: $data['id'],
            boardId: $data['boardId'] ?? '',
            createdAt: $this->convertToDateTime($data['createdAt'] ?? null),
            updatedAt: $this->convertToDateTime($data['updatedAt'] ?? null),
            position: (int) ($data['position'] ?? 0),
            name: $data['name'] ?? null,
            type: $typeEnum,
            color: $colorEnum,
            _rawResponse: $data,
        );
    }
}
