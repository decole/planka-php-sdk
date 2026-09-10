<?php

declare(strict_types=1);

namespace Planka\Bridge\Contracts\Resources;

use Planka\Bridge\Builders\ProjectBuilder;
use Planka\Bridge\Enum\ProjectTypeEnum;
use Planka\Bridge\Inputs\PatchInputInterface;
use Planka\Bridge\Inputs\ProjectCreateInput;
use Planka\Bridge\Inputs\ProjectPatchInput;
use Planka\Bridge\Views\Dto\Background\BackgroundImageDto;
use Planka\Bridge\Views\Dto\Project\ProjectDto;
use Planka\Bridge\Views\Dto\Project\ProjectListDto;

interface ProjectResourceInterface
{
    public function list(): ProjectListDto;

    public function builder(?string $name = null): ProjectBuilder;

    public function create(
        string|ProjectCreateInput|ProjectBuilder $nameOrInput,
        ProjectTypeEnum $type = ProjectTypeEnum::PRIVATE,
        ?string $description = null,
    ): ProjectDto;

    public function get(string $projectId): ProjectDto;

    public function update(ProjectDto $project): ProjectDto;

    /**
     * @param array<string, mixed>|ProjectPatchInput|PatchInputInterface $map
     */
    public function patching(string $projectId, array|PatchInputInterface $map): ProjectDto;

    public function delete(string $projectId): ProjectDto;

    public function updateBackgroundImage(string $projectId, string $file): ProjectDto;

    public function deleteBackgroundImage(string $imageId): ?BackgroundImageDto;
}
