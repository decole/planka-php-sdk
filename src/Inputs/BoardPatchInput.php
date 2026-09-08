<?php

declare(strict_types=1);

namespace Planka\Bridge\Inputs;

final class BoardPatchInput implements PatchInputInterface
{
    public function __construct(
        public readonly ?string $name = null,
        public readonly ?int $position = null,
        public readonly ?string $defaultView = null,
        public readonly ?string $defaultCardType = null,
        public readonly ?bool $limitCardTypesToDefaultOne = null,
        public readonly ?bool $alwaysDisplayCardCreator = null,
        public readonly ?bool $expandTaskListsByDefault = null,
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'position' => $this->position,
            'defaultView' => $this->defaultView,
            'defaultCardType' => $this->defaultCardType,
            'limitCardTypesToDefaultOne' => $this->limitCardTypesToDefaultOne,
            'alwaysDisplayCardCreator' => $this->alwaysDisplayCardCreator,
            'expandTaskListsByDefault' => $this->expandTaskListsByDefault,
        ], fn ($v) => null !== $v);
    }
}
