<?php

declare(strict_types=1);

namespace Planka\Bridge\Controllers;

use Planka\Bridge\Actions\Board\BoardCreateAction;
use Planka\Bridge\Actions\Board\BoardDeleteAction;
use Planka\Bridge\Actions\Board\BoardUpdateAction;
use Planka\Bridge\Actions\Board\BoardViewAction;
use Planka\Bridge\Views\Dto\Board\BoardDto;
use Planka\Bridge\TransportClients\Client;
use Planka\Bridge\Config;

final class Board
{
    public function __construct(
        private readonly Config $config,
        private readonly Client $client
    ) {
    }

    /** 'POST /api/projects/:projectId/boards' */
    public function create(string $projectId, string $name, int $position): BoardDto
    {
        return $this->client->post(new BoardCreateAction(
            token: $this->config->getAuthToken(),
            projectId: $projectId,
            name: $name,
            position: $position
        ));
    }

    /** 'GET /api/boards/:id' */
    public function get(string $boardId): BoardDto
    {
        return $this->client->get(new BoardViewAction(token: $this->config->getAuthToken(), boardId: $boardId));
    }

    /** 'PATCH /api/boards/:id' */
    public function update(string $boardId, string $name): BoardDto
    {
        return $this->client->patch(new BoardUpdateAction(
            token: $this->config->getAuthToken(),
            boardId: $boardId,
            name: $name
        ));
    }

    /** 'DELETE /api/boards/:id' */
    public function delete(string $boardId): BoardDto
    {
        return $this->client->delete(new BoardDeleteAction(token: $this->config->getAuthToken(), boardId: $boardId));
    }
}