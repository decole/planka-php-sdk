<?php

declare(strict_types=1);

namespace Planka\Bridge\Inputs;

use Planka\Bridge\Config;
use Planka\Bridge\Enum\BoardDefaultCardTypeEnum;

final class CardPatchInput implements PatchInputInterface
{
    public function __construct(
        public readonly ?string $name = null,
        public readonly ?string $description = null,
        public readonly string|\DateTimeInterface|null $dueDate = null,
        public readonly ?bool $isDueCompleted = null,
        public readonly ?int $position = null,
        public readonly ?string $listId = null,
        public readonly ?bool $isClosed = null,
        public readonly string|BoardDefaultCardTypeEnum|null $type = null,
        public readonly ?array $stopwatch = null,
    ) {}

    public function toArray(): array
    {
        $dueDateStr = $this->dueDate;

        if ($this->dueDate instanceof \DateTimeInterface) {
            $dueDateStr = $this->dueDate->format(Config::DATE_FORMAT);
        }

        $typeVal = $this->type;

        if ($this->type instanceof BoardDefaultCardTypeEnum) {
            $typeVal = $this->type->value;
        }

        return array_filter([
            'name' => $this->name,
            'description' => $this->description,
            'dueDate' => $dueDateStr,
            'isDueCompleted' => $this->isDueCompleted,
            'position' => $this->position,
            'listId' => $this->listId,
            'isClosed' => $this->isClosed,
            'type' => $typeVal,
            'stopwatch' => $this->stopwatch,
        ], static fn ($v) => null !== $v);
    }
}
