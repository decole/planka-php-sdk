<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\Card;

use Planka\Bridge\Contracts\Dto\OutputDtoInterface;
use DateTimeImmutable;

class CardTaskDto implements OutputDtoInterface
{
    public function __construct(
        public readonly string $id,
        public readonly DateTimeImmutable $createdAt,
        public readonly ?DateTimeImmutable $updatedAt,
        public int $position,
        public string $name,
        public bool $isCompleted,
        public string $cardId
    ) {
    }
}