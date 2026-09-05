<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\Card;

use Planka\Bridge\Contracts\Dto\OutputDtoInterface;

class TaskListDto implements OutputDtoInterface
{
    public function __construct(
        public readonly string $id,
        public readonly string $cardId,
        public int $position,
        public string $name,
        public bool $showOnFrontOfCard,
        public bool $hideCompletedTasks,
        public ?\DateTimeImmutable $createdAt,
        public ?\DateTimeImmutable $updatedAt,
        public readonly array $_rawResponse = [],
    ) {}
}
