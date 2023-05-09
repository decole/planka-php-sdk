<?php

namespace Planka\Bridge\Controllers;

use Planka\Bridge\Actions\Board\BoardViewAction;
use Planka\Bridge\Config;
use Planka\Bridge\TransportClients\Client;
use Planka\Bridge\Views\Dto\Board\BoardDto;

final class Board
{
    public function __construct(
        private readonly Config $config,
        private readonly Client $client
    ) {
    }

    /** 'POST /api/projects/:projectId/boards': 'boards/create', */
    public function createBoard()
    {

    }

    /**

    'GET /api/boards/:id': 'boards/show',
    'PATCH /api/boards/:id': 'boards/update',
    'DELETE /api/boards/:id': 'boards/delete',

    'POST /api/boards/:boardId/memberships': 'board-memberships/create',
    'PATCH /api/board-memberships/:id': 'board-memberships/update',
    'DELETE /api/board-memberships/:id': 'board-memberships/delete',
     */
    /**
     * 'GET /api/boards/:id'
     */
    public function getBoard(int $boardId): BoardDto
    {
        return $this->client->get(new BoardViewAction(token: $this->config->getAuthToken(), id: $boardId));
    }
}