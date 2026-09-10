<?php

declare(strict_types=1);

namespace Planka\Bridge\Contracts\Resources;

use Planka\Bridge\Views\Dto\Project\ProjectManagerDto;

interface ProjectManagerResourceInterface
{
    public function add(string $projectId, string $userId): ProjectManagerDto;

    public function remove(string $managerId): ProjectManagerDto;
}
