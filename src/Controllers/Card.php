<?php

declare(strict_types=1);

namespace Planka\Bridge\Controllers;

use Planka\Bridge\Actions\Card\CardClearDueDateAction;
use Planka\Bridge\Actions\Card\CardCreateAction;
use Planka\Bridge\Actions\Card\CardDeleteAction;
use Planka\Bridge\Actions\Card\CardMoveAction;
use Planka\Bridge\Actions\Card\CardUpdateAction;
use Planka\Bridge\Actions\Card\CardViewAction;
use Planka\Bridge\TransportClients\Client;
use Planka\Bridge\Views\Dto\Card\CardDto;
use Planka\Bridge\Config;

final class Card
{
    public function __construct(
        private readonly Config $config,
        private readonly Client $client
    ) {
    }

    /** 'POST /api/lists/:listId/cards' */
    public function create(string $listId, string $name, int $position): CardDto
    {
        return $this->client->post(new CardCreateAction(
            listId: $listId,
            name: $name,
            position: $position,
            token: $this->config->getAuthToken()
        ));
    }

    /** 'GET /api/cards/:id' */
    public function get(string $cardId): CardDto
    {
        return $this->client->get(new CardViewAction(cardId: $cardId, token: $this->config->getAuthToken()));
    }

    /** 'PATCH /api/cards/:id' */
    public function update(CardDto $card): CardDto
    {
        return $this->client->patch(new CardUpdateAction(
            card: $card,
            token: $this->config->getAuthToken()
        ));
    }

    /** 'PATCH /api/cards/:id' */
    public function clearTime(CardDto $card): CardDto
    {
        return $this->client->patch(new CardClearDueDateAction(
            card: $card,
            token: $this->config->getAuthToken()
        ));
    }

    /** 'PATCH /api/cards/:id' */
    public function moveCard(CardDto $card): CardDto
    {
        return $this->client->patch(new CardMoveAction(
            card: $card,
            token: $this->config->getAuthToken()
        ));
    }

    /** 'PATCH /api/cards/:id' */
    public function addSpentTime(CardDto $card, int $seconds): CardDto
    {
        return $this->client->patch(new CardUpdateAction(
            card: $card,
            token: $this->config->getAuthToken(),
            spentSeconds: $seconds
        ));
    }

    /** 'DELETE /api/cards/:id' */
    public function delete(string $cardId): void
    {
        $this->client->delete(new CardDeleteAction(cardId: $cardId, token: $this->config->getAuthToken()));
    }
}