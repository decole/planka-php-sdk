<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\Label;

use Planka\Bridge\Contracts\Dto\OutputDtoInterface;
use Planka\Bridge\Enum\LabelColorEnum;
use DateTimeImmutable;

class LabelDto implements OutputDtoInterface
{
    public function __construct(
        public readonly string $id,
        public readonly string $boardId,
        public readonly DateTimeImmutable $createdAt,
        public readonly ?DateTimeImmutable $updatedAt,
        public int $position,
        public string $name,
        public ?LabelColorEnum $color,
    ) {
    }
}