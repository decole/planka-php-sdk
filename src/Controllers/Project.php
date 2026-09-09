<?php

declare(strict_types=1);

namespace Planka\Bridge\Controllers;

use Planka\Bridge\Actions\Common\CommonPatchAction;
use Planka\Bridge\Actions\Project\BackgroundImageDeleteAction;
use Planka\Bridge\Actions\Project\ProjectCreateAction;
use Planka\Bridge\Actions\Project\ProjectDeleteAction;
use Planka\Bridge\Actions\Project\ProjectListAction;
use Planka\Bridge\Actions\Project\ProjectUpdateAction;
use Planka\Bridge\Actions\Project\ProjectUpdateBackgroundImageAction;
use Planka\Bridge\Actions\Project\ProjectViewAction;
use Planka\Bridge\Enum\BackgroundGradientEnum;
use Planka\Bridge\Enum\BackgroundTypeEnum;
use Planka\Bridge\Enum\ProjectTypeEnum;
use Planka\Bridge\Inputs\PatchInputInterface;
use Planka\Bridge\Exceptions\FileExistException;
use Planka\Bridge\TransportClients\TransportClientInterface;
use Planka\Bridge\Views\Dto\Background\BackgroundImageDto;
use Planka\Bridge\Views\Dto\Project\ProjectDto;
use Planka\Bridge\Views\Dto\Project\ProjectListDto;
use Planka\Bridge\Views\Factory\Project\ProjectDtoFactory;

final class Project
{
    public function __construct(private readonly TransportClientInterface $client) {}

    /**
     * 'GET /api/projects'.
     */
    public function list(): ProjectListDto
    {
        return $this->client->get(new ProjectListAction());
    }

    /** 'POST /api/projects' */
    public function create(
        string $name,
        ProjectTypeEnum $type = ProjectTypeEnum::PRIVATE,
        ?string $description = null,
    ): ProjectDto {
        return $this->client->post(new ProjectCreateAction(
            name: $name,
            type: $type,
            description: $description,
        ));
    }

    /** 'GET /api/projects/:id' */
    public function get(string $projectId): ProjectDto
    {
        return $this->client->get(new ProjectViewAction(projectId: $projectId));
    }

    /** 'PATCH /api/projects/:id' */
    public function update(ProjectDto $project): ProjectDto
    {
        return $this->client->patch(new ProjectUpdateAction(
            projectId: $project->id,
            name: $project->name,
        ));
    }

    /**
     * 'PATCH /api/projects/:id' - Partially updates project properties.
     *
     * @param string                    $projectId Project ID
     * @param array|PatchInputInterface $map       Associative array or PatchInputInterface of fields to update
     */
    public function patching(string $projectId, array|PatchInputInterface $map): ProjectDto
    {
        $data = $map instanceof PatchInputInterface ? $map->toArray() : $map;

        if (isset($data['backgroundType']) && $data['backgroundType'] instanceof BackgroundTypeEnum) {
            $data['backgroundType'] = $data['backgroundType']->value;
        }

        if (isset($data['backgroundGradient']) && $data['backgroundGradient'] instanceof BackgroundGradientEnum) {
            $data['backgroundGradient'] = $data['backgroundGradient']->value;
        }

        return $this->client->patch(new CommonPatchAction(
            urlPath: "api/projects/{$projectId}",
            data: $data,
            hydrateCallback: new ProjectDtoFactory(),
        ));
    }

    /** 'DELETE /api/projects/:id' */
    public function delete(string $projectId): ProjectDto
    {
        return $this->client->delete(new ProjectDeleteAction(
            projectId: $projectId,
        ));
    }

    /**
     * 'POST /api/projects/:id/background-images'.
     *
     * @throws FileExistException
     */
    public function updateBackgroundImage(string $projectId, string $file): ProjectDto
    {
        return $this->client->post(new ProjectUpdateBackgroundImageAction(
            projectId: $projectId,
            file: $file,
        ));
    }

    /** 'DELETE /api/background-images/:id' */
    public function deleteBackgroundImage(string $imageId): ?BackgroundImageDto
    {
        return $this->client->delete(new BackgroundImageDeleteAction(
            projectId: $imageId,
        ));
    }
}
