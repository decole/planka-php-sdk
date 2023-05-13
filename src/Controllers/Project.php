<?php

declare(strict_types=1);

namespace Planka\Bridge\Controllers;

use Planka\Bridge\Actions\Project\ProjectCreateAction;
use Planka\Bridge\Actions\Project\ProjectDeleteAction;
use Planka\Bridge\Actions\Project\ProjectListAction;
use Planka\Bridge\Actions\Project\ProjectUpdateAction;
use Planka\Bridge\Actions\Project\ProjectUpdateBackgroundImageAction;
use Planka\Bridge\TransportClients\Client;
use Planka\Bridge\Config;

final class Project
{
    public function __construct(
        private readonly Config $config,
        private readonly Client $client
    ) {
    }

    /** 'GET /api/projects' */
    public function list()
    {
        return $this->client->get(new ProjectListAction(token: $this->config->getAuthToken()));
    }

    /** 'POST /api/projects': 'projects/create', */
    public function create()
    {
        return $this->client->post(new ProjectCreateAction(token: $this->config->getAuthToken()));
    }

    /** 'GET /api/projects/:id': 'projects/show', */
    public function get()
    {
        return $this->client->get(new ProjectViewAction(token: $this->config->getAuthToken()));
    }

    /** 'PATCH /api/projects/:id': 'projects/update', */
    public function update()
    {
        return $this->client->get(new ProjectUpdateAction(token: $this->config->getAuthToken()));
    }

    /** 'DELETE /api/projects/:id': 'projects/delete', */
    public function delete()
    {
        return $this->client->get(new ProjectDeleteAction(token: $this->config->getAuthToken()));
    }

    /** 'POST /api/projects/:id/background-image': 'projects/update-background-image', */
    public function updateBackgroundImage()
    {
        return $this->client->get(new ProjectUpdateBackgroundImageAction(token: $this->config->getAuthToken()));
    }
}