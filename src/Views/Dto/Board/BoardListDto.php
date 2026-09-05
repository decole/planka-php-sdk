<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\Board;

use Planka\Bridge\Enum\ListColorEnum;
use Planka\Bridge\Enum\ListTypeEnum;

class BoardListDto
{
    public function __construct(
        public readonly string $id,
        public readonly \DateTimeImmutable $createdAt,
        public readonly ?\DateTimeImmutable $updatedAt,
        public readonly int $position,
        public readonly string $name,
        public readonly string $boardId,
        public ?ListTypeEnum $type = null,
        public ?ListColorEnum $color = null,
        public readonly array $_rawResponse = [],
    ) {}
}
