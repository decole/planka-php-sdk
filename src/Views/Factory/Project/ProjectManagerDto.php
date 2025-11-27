<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\Project;

class ProjectManagerDto
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
