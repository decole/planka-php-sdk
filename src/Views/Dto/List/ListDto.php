<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\List;

use DateTimeImmutable;

class ListDto
{
    public function __construct(
        public readonly string $id,
        public readonly string $boardId,
        public readonly DateTimeImmutable $createdAt,
        public readonly ?DateTimeImmutable $updatedAt,
        public int $position,
        public string $name,
    ) {
    }
}