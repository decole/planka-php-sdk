<?php

declare(strict_types=1);

namespace Planka\Bridge\Controllers;

use Planka\Bridge\Actions\Project\ProjectUpdateBackgroundImageAction;
use Planka\Bridge\Actions\Project\ProjectUpdateAction;
use Planka\Bridge\Actions\Project\ProjectCreateAction;
use Planka\Bridge\Actions\Project\ProjectDeleteAction;
use Planka\Bridge\Actions\Project\ProjectListAction;
use Planka\Bridge\Actions\Project\ProjectViewAction;
use Planka\Bridge\Views\Dto\Project\ProjectListDto;
use Planka\Bridge\Exceptions\FileExistException;
use Planka\Bridge\Views\Dto\Project\ProjectDto;
use Planka\Bridge\TransportClients\Client;
use Planka\Bridge\Config;

final class Project
{
    public function __construct(
        private readonly Config $config,
        private readonly Client $client
    ) {
    }

    /**
     * 'GET /api/projects'
     * @return ProjectListDto
     */
    public function list(): ProjectListDto
    {
        return $this->client->get(new ProjectListAction(token: $this->config->getAuthToken()));
    }

    /** 'POST /api/projects' */
    public function create(string $name): ProjectDto
    {
        return $this->client->post(new ProjectCreateAction(name: $name, token: $this->config->getAuthToken()));
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

    /** 'DELETE /api/projects/:id' */
    public function delete(string $projectId): ProjectDto
    {
        return $this->client->delete(new ProjectDeleteAction(
            projectId: $projectId,
            token: $this->config->getAuthToken()
        ));
    }

    /**
     * 'POST /api/projects/:id/background-image'
     * @throws FileExistException
     */
    public function updateBackgroundImage(string $projectId, string $file): ProjectDto
    {
        return $this->client->post(new ProjectUpdateBackgroundImageAction(
            projectId: $projectId,
            file: $file,
            token: $this->config->getAuthToken()
        ));
    }
}