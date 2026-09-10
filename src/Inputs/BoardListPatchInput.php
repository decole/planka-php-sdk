<?php

declare(strict_types=1);

namespace Planka\Bridge\Inputs;

use Planka\Bridge\Enum\ListColorEnum;
use Planka\Bridge\Enum\ListTypeEnum;

final class BoardListPatchInput implements PatchInputInterface
{
    public function __construct(
        public readonly ?string $name = null,
        public readonly ?int $position = null,
        public readonly string|ListTypeEnum|null $type = null,
        public readonly string|ListColorEnum|null $color = null,
    ) {}

    public function toArray(): array
    {
        $typeVal = $this->type;

        if ($this->type instanceof ListTypeEnum) {
            $typeVal = $this->type->value;
        }

        $colorVal = $this->color;

        if ($this->color instanceof ListColorEnum) {
            $colorVal = $this->color->value;
        }

        return array_filter([
            'name' => $this->name,
            'position' => $this->position,
            'type' => $typeVal,
            'color' => $colorVal,
        ], static fn ($v) => null !== $v);
    }
}
