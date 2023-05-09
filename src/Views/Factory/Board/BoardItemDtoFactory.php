<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\Board;

use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Traits\DateConverterTrait;
use Planka\Bridge\Views\Dto\Board\BoardItemDto;

class BoardItemDtoFactory implements OutputInterface
{
    use DateConverterTrait;

    public function create(array $data): ?BoardItemDto
    {
        $item = $data['item'] ?? null;

        if ($item === null) {
            return null;
        }

        return new BoardItemDto(
            id: $item['id'],
            projectId: $item['projectId'],
            position: $item['position'],
            name: $item['name'],
            createdAt: $this->convertToDateTime($item['createdAt']),
            updatedAt: $this->convertToDateTime($item['updatedAt'])
        );
    }
}