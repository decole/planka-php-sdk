<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\Project;

use Planka\Bridge\Contracts\Dto\OutputDtoInterface;
use Planka\Bridge\Enum\BackgroundGradientEnum;
use Planka\Bridge\Enum\BackgroundTypeEnum;
use Planka\Bridge\Views\Dto\Background\BackgroundDto;
use Planka\Bridge\Views\Dto\Background\BackgroundImageDto;

class ProjectDto implements OutputDtoInterface
{
    public function __construct(
        public readonly string $id,
        public readonly \DateTimeImmutable $createdAt,
        public readonly ?\DateTimeImmutable $updatedAt,
        public string $name,
        public ?BackgroundDto $background,
        public ?BackgroundImageDto $backgroundImage,
        public ?string $ownerProjectManagerId = null,
        public ?string $backgroundImageId = null,
        public ?string $description = null,
        public bool $isHidden = false,
        public ?BackgroundTypeEnum $backgroundType = null,
        public ?BackgroundGradientEnum $backgroundGradient = null,
        public readonly array $_rawResponse = [],
    ) {}
}
