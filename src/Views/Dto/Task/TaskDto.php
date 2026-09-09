<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\Task;

use Planka\Bridge\Contracts\Dto\OutputDtoInterface;
use Planka\Bridge\Traits\OutputDtoTrait;

class TaskDto implements OutputDtoInterface
{
    use OutputDtoTrait;

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
        /** @var array<string, mixed> Diagnostic raw response array from Planka API to verify DTO field hydration. */
        public readonly array $_rawResponse = [],
    ) {}
}
