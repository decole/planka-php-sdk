<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\Card;

use Planka\Bridge\Contracts\Dto\OutputDtoInterface;

class CardDto implements OutputDtoInterface
{
    public function __construct(
        public readonly string $id,
        public readonly \DateTimeImmutable $createdAt,
        public readonly ?\DateTimeImmutable $updatedAt,
        public int $position,
        public string $name,
        public ?string $description,
        public ?\DateTimeImmutable $dueDate,
        public readonly ?bool $isDueDateCompleted,
        public ?StopWatchDto $stopwatch,
        public string $boardId,
        public string $listId,
        public string $creatorUserId,
        public ?string $coverAttachmentId,
        public bool $isSubscribed,
        public readonly CardIncludedDto $included,
    ) {}
}
