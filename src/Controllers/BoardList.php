<?php

declare(strict_types=1);

namespace Planka\Bridge\Controllers;

use Planka\Bridge\Actions\BoardList\BoardListClearAction;
use Planka\Bridge\Actions\BoardList\BoardListCreateAction;
use Planka\Bridge\Actions\BoardList\BoardListDeleteAction;
use Planka\Bridge\Actions\BoardList\BoardListMoveCardsAction;
use Planka\Bridge\Actions\BoardList\BoardListSortAction;
use Planka\Bridge\Actions\BoardList\BoardListUpdateAction;
use Planka\Bridge\Config;
use Planka\Bridge\TransportClients\Client;
use Planka\Bridge\Views\Dto\Board\BoardListDto;

final class BoardList
{
    public function __construct(
        private readonly Config $config,
        private readonly Client $client,
    ) {}

    /** 'POST /api/boards/:boardId/lists' */
    public function create(
        string $boardId,
        string $name,
        int $position,
        \Planka\Bridge\Enum\ListTypeEnum $type = \Planka\Bridge\Enum\ListTypeEnum::ACTIVE,
    ): BoardListDto {
        return $this->client->post(new BoardListCreateAction(
            boardId: $boardId,
            name: $name,
            position: $position,
            type: $type,
        ));
    }

    /** 'PATCH /api/lists/:id' */
    public function update(string $listId, string $name): BoardListDto
    {
        return $this->client->patch(new BoardListUpdateAction(
            listId: $listId,
            name: $name,
            token: $this->config->getAuthToken(),
        ));
    }

    /** 'DELETE /api/lists/:id' */
    public function delete(string $listId): BoardListDto
    {
        return $this->client->delete(new BoardListDeleteAction(listId: $listId, token: $this->config->getAuthToken()));
    }

    /** 'POST /api/lists/:id/clear' */
    public function clear(string $listId): BoardListDto
    {
        return $this->client->post(new BoardListClearAction(listId: $listId));
    }

    /** 'POST /api/lists/:id/move-cards' */
    public function moveCards(string $sourceListId, string $targetListId): BoardListDto
    {
        return $this->client->post(new BoardListMoveCardsAction(sourceListId: $sourceListId, targetListId: $targetListId));
    }

    /** 'POST /api/lists/:id/sort' */
    public function sort(string $listId, string $fieldName, string $order = 'asc'): BoardListDto
    {
        return $this->client->post(new BoardListSortAction(listId: $listId, fieldName: $fieldName, order: $order));
    }
}
