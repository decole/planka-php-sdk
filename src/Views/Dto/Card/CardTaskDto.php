<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\Card;

use Planka\Bridge\Contracts\Dto\OutputDtoInterface;

class CardTaskDto implements OutputDtoInterface
{
    public function __construct(
        public readonly string $id,
        public readonly string $taskListId,
        public readonly ?string $linkedCardId,
        public readonly ?string $assigneeUserId,
        public int $position,
        public string $name,
        public bool $isCompleted,
        public ?\DateTimeImmutable $createdAt = null,
        public ?\DateTimeImmutable $updatedAt = null,
    ) {}
}
