<?php

declare(strict_types=1);

namespace Planka\Bridge\Controllers;

use Planka\Bridge\Actions\Card\CardCreateAction;
use Planka\Bridge\Actions\Card\CardDeleteAction;
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
            token: $this->config->getAuthToken(),
            listId: $listId,
            name: $name,
            position: $position
        ));
    }

    /** 'GET /api/cards/:id' */
    public function get(string $cardId): CardDto
    {
        return $this->client->get(new CardViewAction(token: $this->config->getAuthToken(), cardId: $cardId));
    }

    /** 'PATCH /api/cards/:id' */
    public function update(string $cardId, CardDto $card): CardDto
    {
        return $this->client->patch(new CardUpdateAction(
            token: $this->config->getAuthToken(),
            cardId: $cardId,
            card: $card
        ));
    }

    /** 'PATCH /api/cards/:id' */
    public function addSpentTime(string $cardId, CardDto $card, int $seconds): CardDto
    {
        return $this->client->patch(new CardUpdateAction(
            token: $this->config->getAuthToken(),
            cardId: $cardId,
            card: $card,
            spentSeconds: $seconds
        ));
    }

    /** 'DELETE /api/cards/:id' */
    public function delete(string $cardId): void
    {
        $this->client->delete(new CardDeleteAction(token: $this->config->getAuthToken(), cardId: $cardId));
    }
}