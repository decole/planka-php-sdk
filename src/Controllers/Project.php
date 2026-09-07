<?php

declare(strict_types=1);

namespace Planka\Bridge\Controllers;

use Planka\Bridge\Actions\Common\CommonPatchAction;
use Planka\Bridge\Actions\Project\ProjectCreateAction;
use Planka\Bridge\Actions\Project\ProjectDeleteAction;
use Planka\Bridge\Actions\Project\ProjectListAction;
use Planka\Bridge\Actions\Project\ProjectUpdateAction;
use Planka\Bridge\Actions\Project\ProjectUpdateBackgroundImageAction;
use Planka\Bridge\Actions\Project\ProjectViewAction;
use Planka\Bridge\Config;
use Planka\Bridge\Enum\BackgroundGradientEnum;
use Planka\Bridge\Enum\BackgroundTypeEnum;
use Planka\Bridge\Exceptions\FileExistException;
use Planka\Bridge\Traits\ProjectHydrateTrait;
use Planka\Bridge\TransportClients\Client;
use Planka\Bridge\Views\Dto\Project\ProjectDto;
use Planka\Bridge\Views\Dto\Project\ProjectListDto;

final class Project
{
    use ProjectHydrateTrait;

    public function __construct(
        private readonly Config $config,
        private readonly Client $client,
    ) {}

    /**
     * 'GET /api/projects'.
     */
    public function list(): ProjectListDto
    {
        return $this->client->get(new ProjectListAction(token: $this->config->getAuthToken()));
    }

    /** 'POST /api/projects' */
    public function create(
        string $name,
        \Planka\Bridge\Enum\ProjectTypeEnum $type = \Planka\Bridge\Enum\ProjectTypeEnum::PRIVATE,
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
        return $this->client->get(new ProjectViewAction(projectId: $projectId, token: $this->config->getAuthToken()));
    }

    /** 'PATCH /api/projects/:id' */
    public function update(ProjectDto $project): ProjectDto
    {
        return $this->client->patch(new ProjectUpdateAction(
            project: $project,
            token: $this->config->getAuthToken(),
        ));
    }

    /**
     * 'PATCH /api/projects/:id' - Partially updates project properties.
     *
     * @param string $projectId Project ID
     * @param array{
     *   name?: string,
     *   description?: string|null,
     *   backgroundType?: 'gradient'|'image'|BackgroundTypeEnum|null,
     *   backgroundGradient?: string|BackgroundGradientEnum|null,
     *   backgroundImageId?: string|null,
     *   isHidden?: bool
     * } $map Associative array of fields to update
     */
    public function patching(string $projectId, array $map): ProjectDto
    {
        if (isset($map['backgroundType']) && $map['backgroundType'] instanceof BackgroundTypeEnum) {
            $map['backgroundType'] = $map['backgroundType']->value;
        }

        if (isset($map['backgroundGradient']) && $map['backgroundGradient'] instanceof BackgroundGradientEnum) {
            $map['backgroundGradient'] = $map['backgroundGradient']->value;
        }

        return $this->client->patch(new CommonPatchAction(
            urlPath: "api/projects/{$projectId}",
            data: $map,
            hydrateCallback: fn($response) => $this->hydrate($response),
        ));
    }

    /** 'DELETE /api/projects/:id' */
    public function delete(string $projectId): ProjectDto
    {
        return $this->client->delete(new ProjectDeleteAction(
            projectId: $projectId,
            token: $this->config->getAuthToken(),
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
            token: $this->config->getAuthToken(),
        ));
    }

    /** 'DELETE /api/background-images/:id' */
    public function deleteBackgroundImage(string $imageId): array
    {
        return $this->client->delete(new \Planka\Bridge\Actions\Project\BackgroundImageDeleteAction(
            imageId: $imageId,
            token: $this->config->getAuthToken(),
        ));
    }
}
