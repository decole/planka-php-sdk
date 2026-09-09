<?php

declare(strict_types=1);

namespace Planka\Bridge\Inputs;

use Planka\Bridge\Enum\BoardDefaultCardTypeEnum;
use Planka\Bridge\Enum\BoardDefaultViewEnum;

final class BoardCreateInput
{
    public function __construct(
        public readonly string $name,
        public readonly int $position = 65536,
        public readonly ?BoardDefaultViewEnum $defaultView = null,
        public readonly ?BoardDefaultCardTypeEnum $defaultCardType = null,
        public readonly ?bool $limitCardTypesToDefaultOne = null,
        public readonly ?bool $alwaysDisplayCardCreator = null,
        public readonly ?bool $expandTaskListsByDefault = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'position' => $this->position,
            'defaultView' => $this->defaultView?->value,
            'defaultCardType' => $this->defaultCardType?->value,
            'limitCardTypesToDefaultOne' => $this->limitCardTypesToDefaultOne,
            'alwaysDisplayCardCreator' => $this->alwaysDisplayCardCreator,
            'expandTaskListsByDefault' => $this->expandTaskListsByDefault,
        ], static fn ($v) => null !== $v);
    }
}
