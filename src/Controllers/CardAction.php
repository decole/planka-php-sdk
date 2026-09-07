<?php

declare(strict_types=1);

namespace Planka\Bridge\Controllers;

use Planka\Bridge\Actions\CardAction\CardActionViewAction;
use Planka\Bridge\Views\Dto\Card\CardActionListDto;
use Planka\Bridge\TransportClients\TransportClientInterface;

final class CardAction
{
    public function __construct(
        private readonly TransportClientInterface $client,
    ) {}

    /** 'GET /api/cards/:cardId/actions' */
    public function getActions(string $cardId): CardActionListDto
    {
        return $this->client->get(new CardActionViewAction(cardId: $cardId));
    }

    /** 'GET /api/boards/:boardId/actions' */
    public function getBoardActions(string $boardId, ?string $beforeId = null): CardActionListDto
    {
        return $this->client->get(new \Planka\Bridge\Actions\CardAction\BoardActionListAction(
            boardId: $boardId,
            beforeId: $beforeId,
        ));
    }
}
