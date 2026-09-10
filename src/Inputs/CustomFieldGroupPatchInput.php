<?php

declare(strict_types=1);

namespace Planka\Bridge\Inputs;

final class CustomFieldGroupPatchInput implements PatchInputInterface
{
    public function __construct(
        public readonly ?string $name = null,
        public readonly ?int $position = null,
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'position' => $this->position,
        ], static fn ($v) => null !== $v);
    }
}
