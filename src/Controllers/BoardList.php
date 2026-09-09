<?php

declare(strict_types=1);

namespace Planka\Bridge\Controllers;

use Planka\Bridge\Actions\BoardList\BoardListClearAction;
use Planka\Bridge\Actions\BoardList\BoardListCreateAction;
use Planka\Bridge\Actions\BoardList\BoardListDeleteAction;
use Planka\Bridge\Actions\BoardList\BoardListMoveCardsAction;
use Planka\Bridge\Actions\BoardList\BoardListSortAction;
use Planka\Bridge\Actions\BoardList\BoardListUpdateAction;
use Planka\Bridge\Actions\Common\CommonPatchAction;
use Planka\Bridge\Enum\ListTypeEnum;
use Planka\Bridge\TransportClients\TransportClientInterface;
use Planka\Bridge\Views\Dto\Board\BoardListDto;
use Planka\Bridge\Views\Factory\Board\BoardListDtoFactory;

final class BoardList
{
    public function __construct(private readonly TransportClientInterface $client) {}

    /** 'POST /api/boards/:boardId/lists' */
    public function create(
        string $boardId,
        string $name,
        int $position,
        ListTypeEnum $type = ListTypeEnum::ACTIVE,
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
        ));
    }

    /**
     * 'PATCH /api/lists/:id' - Partially updates list properties.
     *
     * @param string $listId List ID
     * @param array{
     *   name?: string,
     *   position?: int,
     *   type?: 'active'|'closed'|'archive'|'trash',
     *   color?: string|null
     * } $map Associative array of fields to update
     */
    public function patching(string $listId, array $map): BoardListDto
    {
        return $this->client->patch(new CommonPatchAction(
            urlPath: "api/lists/{$listId}",
            data: $map,
            hydrateCallback: new BoardListDtoFactory(),
        ));
    }

    /** 'DELETE /api/lists/:id' */
    public function delete(string $listId): BoardListDto
    {
        return $this->client->delete(new BoardListDeleteAction(listId: $listId));
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
