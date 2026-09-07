<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\Project;

use Planka\Bridge\Contracts\Dto\OutputDtoInterface;

class ProjectManagerDto implements OutputDtoInterface
{
    public function __construct(
        public readonly string $id,
        public readonly \DateTimeImmutable $createdAt,
        public readonly ?\DateTimeImmutable $updatedAt,
        public readonly string $projectId,
        public readonly string $userId,
        public readonly array $_rawResponse = [],
    ) {}
}
