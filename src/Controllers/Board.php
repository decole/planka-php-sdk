<?php

declare(strict_types=1);

namespace Planka\Bridge\Controllers;

use Planka\Bridge\Actions\Board\BoardCreateAction;
use Planka\Bridge\Actions\Board\BoardDeleteAction;
use Planka\Bridge\Actions\Board\BoardUpdateAction;
use Planka\Bridge\Actions\Board\BoardViewAction;
use Planka\Bridge\Actions\CardAction\BoardActionListAction;
use Planka\Bridge\Actions\Common\CommonPatchAction;
use Planka\Bridge\Inputs\PatchInputInterface;
use Planka\Bridge\TransportClients\TransportClientInterface;
use Planka\Bridge\Views\Dto\Board\BoardDto;
use Planka\Bridge\Views\Dto\Card\CardActionListDto;
use Planka\Bridge\Views\Factory\Board\BoardDtoFactory;

final class Board
{
    public function __construct(
        private readonly TransportClientInterface $client,
    ) {}

    /** 'POST /api/projects/:projectId/boards' */
    public function create(string $projectId, string $name, int $position): BoardDto
    {
        return $this->client->post(new BoardCreateAction(
            projectId: $projectId,
            name: $name,
            position: $position,
        ));
    }

    /** 'GET /api/boards/:id' */
    public function get(string $boardId): BoardDto
    {
        return $this->client->get(new BoardViewAction(boardId: $boardId));
    }

    /** 'PATCH /api/boards/:id' */
    public function update(string $boardId, string $name): BoardDto
    {
        return $this->client->patch(new BoardUpdateAction(
            boardId: $boardId,
            data: ['name' => $name],
        ));
    }

    /**
     * 'PATCH /api/boards/:id' - Partially updates board properties.
     *
     * @param string                    $boardId Board ID
     * @param array|PatchInputInterface $map     Associative array or PatchInputInterface of fields to update
     */
    public function patching(string $boardId, array|PatchInputInterface $map): BoardDto
    {
        $data = $map instanceof PatchInputInterface ? $map->toArray() : $map;

        return $this->client->patch(new CommonPatchAction(
            urlPath: "api/boards/{$boardId}",
            data: $data,
            hydrateCallback: new BoardDtoFactory(),
        ));
    }

    /** 'DELETE /api/boards/:id' */
    public function delete(string $boardId): BoardDto
    {
        return $this->client->delete(new BoardDeleteAction(boardId: $boardId));
    }

    /** 'GET /api/boards/:boardId/actions' */
    public function getActions(string $boardId, ?string $beforeId = null): CardActionListDto
    {
        return $this->client->get(new BoardActionListAction(
            boardId: $boardId,
            beforeId: $beforeId,
        ));
    }
}
