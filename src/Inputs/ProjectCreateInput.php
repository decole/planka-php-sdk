<?php

declare(strict_types=1);

namespace Planka\Bridge\Inputs;

use Planka\Bridge\Enum\BackgroundGradientEnum;
use Planka\Bridge\Enum\BackgroundTypeEnum;
use Planka\Bridge\Enum\ProjectTypeEnum;

final class ProjectCreateInput
{
    public function __construct(
        public readonly string $name,
        public readonly ?ProjectTypeEnum $type = null,
        public readonly ?string $description = null,
        public readonly ?BackgroundTypeEnum $backgroundType = null,
        public readonly ?BackgroundGradientEnum $backgroundGradient = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'type' => $this->type?->value,
            'description' => $this->description,
            'backgroundType' => $this->backgroundType?->value,
            'backgroundGradient' => $this->backgroundGradient?->value,
        ], static fn ($v) => null !== $v);
    }
}
