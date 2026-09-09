<?php

declare(strict_types=1);

namespace Planka\Bridge\Inputs;

use Planka\Bridge\Enum\BackgroundGradientEnum;
use Planka\Bridge\Enum\BackgroundTypeEnum;

final class ProjectPatchInput implements PatchInputInterface
{
    public function __construct(
        public readonly ?string $name = null,
        public readonly ?string $description = null,
        public readonly BackgroundTypeEnum|string|null $backgroundType = null,
        public readonly BackgroundGradientEnum|string|null $backgroundGradient = null,
        public readonly ?string $backgroundImageId = null,
        public readonly ?bool $isHidden = null,
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'description' => $this->description,
            'backgroundType' => $this->backgroundType instanceof BackgroundTypeEnum ? $this->backgroundType->value : $this->backgroundType,
            'backgroundGradient' => $this->backgroundGradient instanceof BackgroundGradientEnum ? $this->backgroundGradient->value : $this->backgroundGradient,
            'backgroundImageId' => $this->backgroundImageId,
            'isHidden' => $this->isHidden,
        ], static fn ($v) => null !== $v);
    }
}
