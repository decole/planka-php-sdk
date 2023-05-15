<?php

declare(strict_types=1);

namespace Planka\Bridge\Controllers;

use Planka\Bridge\Actions\BoardList\BoardListCreateAction;
use Planka\Bridge\Actions\BoardList\BoardListDeleteAction;
use Planka\Bridge\Actions\BoardList\BoardListUpdateAction;
use Planka\Bridge\Views\Dto\Board\BoardListDto;
use Planka\Bridge\TransportClients\Client;
use Planka\Bridge\Config;

final class BoardList
{
    public function __construct(
        private readonly Config $config,
        private readonly Client $client
    ) {
    }

    /** 'POST /api/boards/:boardId/lists' */
    public function create(string $boardId, string $name, int $position): BoardListDto
    {
        return $this->client->post(new BoardListCreateAction(
            boardId: $boardId,
            name: $name,
            position: $position,
            token: $this->config->getAuthToken()
        ));
    }

    /** 'PATCH /api/lists/:id' */
    public function update(string $listId, string $name): BoardListDto
    {
        return $this->client->patch(new BoardListUpdateAction(
            listId: $listId,
            name: $name,
            token: $this->config->getAuthToken()
        ));
    }

    /** 'DELETE /api/lists/:id' */
    public function delete(string $listId): BoardListDto
    {
        return $this->client->delete(new BoardListDeleteAction(listId: $listId, token: $this->config->getAuthToken()));
    }
}