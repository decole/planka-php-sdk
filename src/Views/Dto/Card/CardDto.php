<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\Card;

use Planka\Bridge\Config;
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
        /** @var array<string, mixed> Diagnostic raw response array from Planka API to verify DTO field hydration. */
        public readonly array $_rawResponse = [],
    ) {}

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'description' => $this->description,
            'dueDate' => $this->dueDate?->format(Config::DATE_FORMAT),
            'isDueCompleted' => $this->isDueCompleted ?? $this->isDueDateCompleted,
            'position' => $this->position,
            'listId' => $this->listId,
            'isClosed' => $this->isClosed,
            'type' => $this->type?->value,
            'stopwatch' => $this->stopwatch ? array_filter([
                'startedAt' => $this->stopwatch->startedAt?->format(Config::DATE_FORMAT),
                'total' => $this->stopwatch->total,
            ], static fn ($v) => null !== $v) : null,
        ], static fn ($v) => null !== $v);
    }
}
