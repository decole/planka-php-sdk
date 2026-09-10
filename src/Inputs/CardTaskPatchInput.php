<?php

declare(strict_types=1);

namespace Planka\Bridge\Inputs;

final class CardTaskPatchInput implements PatchInputInterface
{
    public function __construct(
        public readonly ?string $name = null,
        public readonly ?int $position = null,
        public readonly ?bool $isCompleted = null,
        public readonly ?string $linkedCardId = null,
        public readonly ?string $assigneeUserId = null,
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'position' => $this->position,
            'isCompleted' => $this->isCompleted,
            'linkedCardId' => $this->linkedCardId,
            'assigneeUserId' => $this->assigneeUserId,
        ], static fn ($v) => null !== $v);
    }
}
