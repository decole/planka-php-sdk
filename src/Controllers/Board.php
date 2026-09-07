<?php

declare(strict_types=1);

namespace Planka\Bridge\Controllers;

use Planka\Bridge\Actions\Board\BoardCreateAction;
use Planka\Bridge\Actions\Board\BoardDeleteAction;
use Planka\Bridge\Actions\Board\BoardUpdateAction;
use Planka\Bridge\Actions\Board\BoardViewAction;
use Planka\Bridge\Actions\CardAction\BoardActionListAction;
use Planka\Bridge\Actions\Common\CommonPatchAction;
use Planka\Bridge\Config;
use Planka\Bridge\Traits\BoardHydrateTrait;
use Planka\Bridge\TransportClients\Client;
use Planka\Bridge\Views\Dto\Board\BoardDto;
use Planka\Bridge\Views\Dto\Card\CardActionListDto;

final class Board
{
    use BoardHydrateTrait;

    public function __construct(
        private readonly Config $config,
        private readonly Client $client,
    ) {}

    /** 'POST /api/projects/:projectId/boards' */
    public function create(string $projectId, string $name, int $position): BoardDto
    {
        return $this->client->post(new BoardCreateAction(
            projectId: $projectId,
            name: $name,
            position: $position,
            token: $this->config->getAuthToken(),
        ));
    }

    /** 'GET /api/boards/:id' */
    public function get(string $boardId): BoardDto
    {
        return $this->client->get(new BoardViewAction(boardId: $boardId, token: $this->config->getAuthToken()));
    }

    /** 'PATCH /api/boards/:id' */
    public function update(string $boardId, string $name): BoardDto
    {
        return $this->client->patch(new BoardUpdateAction(
            boardId: $boardId,
            name: $name,
            token: $this->config->getAuthToken(),
        ));
    }

    /**
     * 'PATCH /api/boards/:id' - Partially updates board properties.
     *
     * @param string $boardId Board ID
     * @param array{
     *   name?: string,
     *   position?: int,
     *   defaultView?: 'kanban'|'grid'|'list',
     *   defaultCardType?: 'project'|'story',
     *   limitCardTypesToDefaultOne?: bool,
     *   alwaysDisplayCardCreator?: bool,
     *   expandTaskListsByDefault?: bool
     * } $map Associative array of fields to update
     */
    public function patching(string $boardId, array $map): BoardDto
    {
        return $this->client->patch(new CommonPatchAction(
            urlPath: "api/boards/{$boardId}",
            data: $map,
            hydrateCallback: fn($response) => $this->hydrate($response),
        ));
    }

    /** 'DELETE /api/boards/:id' */
    public function delete(string $boardId): BoardDto
    {
        return $this->client->delete(new BoardDeleteAction(boardId: $boardId, token: $this->config->getAuthToken()));
    }

    /** 'GET /api/boards/:boardId/actions' */
    public function getActions(string $boardId, ?string $beforeId = null): CardActionListDto
    {
        return $this->client->get(new BoardActionListAction(
            boardId: $boardId,
            beforeId: $beforeId,
            token: $this->config->getAuthToken(),
        ));
    }
}
