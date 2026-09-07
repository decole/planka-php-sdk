<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\Card;

use Planka\Bridge\Contracts\Dto\OutputDtoInterface;
use Planka\Bridge\Enum\BoardDefaultCardTypeEnum;

final class CardDto implements OutputDtoInterface
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
        public ?BoardDefaultCardTypeEnum $type = null,
        public ?string $prevListId = null,
        public int $commentsTotal = 0,
        public bool $isClosed = false,
        public ?\DateTimeImmutable $listChangedAt = null,
        public readonly ?bool $isDueCompleted = null,
        public readonly array $_rawResponse = [],
    ) {}

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'description' => $this->description,
            'dueDate' => $this->dueDate?->format('Y-m-d\TH:i:s.v\Z'),
            'isDueCompleted' => $this->isDueCompleted ?? $this->isDueDateCompleted,
            'position' => $this->position,
            'listId' => $this->listId,
            'isClosed' => $this->isClosed,
            'type' => $this->type?->value,
            'stopwatch' => $this->stopwatch ? array_filter([
                'startedAt' => $this->stopwatch->startedAt?->format('Y-m-d\TH:i:s.v\Z'),
                'total' => $this->stopwatch->total,
            ], fn ($v) => null !== $v) : null,
        ], fn ($v) => null !== $v);
    }
}
