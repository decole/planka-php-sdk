<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\List;

use Planka\Bridge\Contracts\Dto\OutputDtoInterface;
use Planka\Bridge\Enum\ListColorEnum;
use Planka\Bridge\Enum\ListTypeEnum;

class ListDto implements OutputDtoInterface
{
    public function __construct(
        public readonly string $id,
        public readonly string $boardId,
        public readonly \DateTimeImmutable $createdAt,
        public readonly ?\DateTimeImmutable $updatedAt,
        public int $position,
        public ?string $name = null,
        public ?ListTypeEnum $type = null,
        public ?ListColorEnum $color = null,
        public readonly array $_rawResponse = [],
    ) {}
}
