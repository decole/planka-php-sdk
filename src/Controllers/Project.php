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
use Planka\Bridge\Builders\ProjectBuilder;
use Planka\Bridge\Contracts\Resources\ProjectResourceInterface;
use Planka\Bridge\Enum\ProjectTypeEnum;
use Planka\Bridge\Exceptions\FileExistException;
use Planka\Bridge\Inputs\PatchInputInterface;
use Planka\Bridge\Inputs\PatchInputNormalizer;
use Planka\Bridge\Inputs\ProjectCreateInput;
use Planka\Bridge\TransportClients\TransportClientInterface;
use Planka\Bridge\Views\Dto\Background\BackgroundImageDto;
use Planka\Bridge\Views\Dto\Project\ProjectDto;
use Planka\Bridge\Views\Dto\Project\ProjectListDto;
use Planka\Bridge\Views\Factory\Project\ProjectDtoFactory;

final class Project implements ProjectResourceInterface
{
    public function __construct(private readonly TransportClientInterface $client) {}

    /**
     * 'GET /api/projects'.
     */
    public function list(): ProjectListDto
    {
        return $this->client->get(new ProjectListAction());
    }

    public function builder(?string $name = null): ProjectBuilder
    {
        return new ProjectBuilder($name);
    }

    /** 'POST /api/projects' */
    public function create(
        string|ProjectCreateInput|ProjectBuilder $nameOrInput,
        ProjectTypeEnum $type = ProjectTypeEnum::PRIVATE,
        ?string $description = null,
    ): ProjectDto {
        if ($nameOrInput instanceof ProjectBuilder) {
            $nameOrInput = $nameOrInput->build();
        }

        if ($nameOrInput instanceof ProjectCreateInput) {
            return $this->client->post(new CommonPatchAction(
                urlPath: 'api/projects',
                data: $nameOrInput->toArray(),
                hydrateCallback: new ProjectDtoFactory(),
            ));
        }

        return $this->client->post(new ProjectCreateAction(
            name: $nameOrInput,
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
        return $this->client->patch(new CommonPatchAction(
            urlPath: "api/projects/{$projectId}",
            data: PatchInputNormalizer::normalize($map),
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
