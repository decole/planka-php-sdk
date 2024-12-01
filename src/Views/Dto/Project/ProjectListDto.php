<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\Project;

class ProjectListDto
{
    /**
     * @param list<ProjectDto> $items
     */
    public function __construct(
        public readonly array $items,
        public readonly ProjectIncludedDto $included,
    ) {}
}
