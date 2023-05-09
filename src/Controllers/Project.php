<?php

namespace Planka\Bridge\Controllers;

use Planka\Bridge\Config;
use Planka\Bridge\TransportClients\Client;

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
    }

    /** 'POST /api/projects': 'projects/create', */
    public function create()
    {

    }

    /** 'GET /api/projects/:id': 'projects/show', */
    public function get()
    {

    }

    /** 'PATCH /api/projects/:id': 'projects/update', */
    public function update()
    {

    }

    /** 'POST /api/projects/:id/background-image': 'projects/update-background-image', */
    public function updateBackgroundImage()
    {

    }

    /** 'DELETE /api/projects/:id': 'projects/delete', */
    public function delete()
    {

    }

    /** 'POST /api/projects/:projectId/managers': 'project-managers/create', */
    public function createProjectManager()
    {

    }

    /** 'DELETE /api/project-managers/:id': 'project-managers/delete', */
    public function deleteProjectManager()
    {

    }
}