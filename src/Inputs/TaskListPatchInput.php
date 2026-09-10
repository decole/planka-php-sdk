<?php

declare(strict_types=1);

namespace Planka\Bridge\Inputs;

final class TaskListPatchInput implements PatchInputInterface
{
    public function __construct(
        public readonly ?string $name = null,
        public readonly ?int $position = null,
        public readonly ?bool $showOnFrontOfCard = null,
        public readonly ?bool $hideCompletedTasks = null,
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'position' => $this->position,
            'showOnFrontOfCard' => $this->showOnFrontOfCard,
            'hideCompletedTasks' => $this->hideCompletedTasks,
        ], static fn ($v) => null !== $v);
    }
}
