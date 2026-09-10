<?php

declare(strict_types=1);

namespace Planka\Bridge\Inputs;

final class AttachmentPatchInput implements PatchInputInterface
{
    public function __construct(
        public readonly ?string $name = null,
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
        ], static fn ($v) => null !== $v);
    }
}
