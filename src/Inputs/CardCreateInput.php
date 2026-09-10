<?php

declare(strict_types=1);

namespace Planka\Bridge\Inputs;

use Planka\Bridge\Config;
use Planka\Bridge\Enum\BoardDefaultCardTypeEnum;

final class CardCreateInput
{
    public function __construct(
        public readonly string $name,
        public readonly ?int $position = 65536,
        public readonly ?string $description = null,
        public readonly ?\DateTimeImmutable $dueDate = null,
        public readonly ?bool $isDueCompleted = null,
        public readonly ?BoardDefaultCardTypeEnum $type = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'position' => $this->position,
            'description' => $this->description,
            'dueDate' => $this->dueDate?->format(Config::DATE_FORMAT),
            'isDueCompleted' => $this->isDueCompleted,
            'type' => $this->type?->value,
        ], static fn ($v) => null !== $v);
    }
}
