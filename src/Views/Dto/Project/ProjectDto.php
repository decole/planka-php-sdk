<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\Project;

use Planka\Bridge\Views\Dto\Background\BackgroundImageDto;
use Planka\Bridge\Views\Dto\Background\BackgroundDto;
use Planka\Bridge\Contracts\Dto\OutputDtoInterface;
use DateTimeImmutable;

class ProjectDto implements OutputDtoInterface
{
    public function __construct(
        public readonly string $id,
        public readonly DateTimeImmutable $createdAt,
        public readonly ?DateTimeImmutable $updatedAt,
        public string $name,
        public ?BackgroundDto $background,
        public ?BackgroundImageDto $backgroundImage
    ) {
    }
}