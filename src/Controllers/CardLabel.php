<?php

declare(strict_types=1);

namespace Planka\Bridge\Controllers;

use Planka\Bridge\Actions\CardLabel\CardLabelCreateAction;
use Planka\Bridge\Actions\CardLabel\CardLabelDeleteAction;
use Planka\Bridge\Config;
use Planka\Bridge\TransportClients\Client;
use Planka\Bridge\Views\Dto\Card\CardLabelDto;

final class CardLabel
{
    public function __construct(
        private readonly Config $config,
        private readonly Client $client
    ) {
    }

    /** 'POST /api/cards/:cardId/labels' */
    public function add(string $cardId, string $labelId): CardLabelDto
    {
        return $this->client->post(new CardLabelCreateAction(
            token: $this->config->getAuthToken(),
            cardId: $cardId,
            labelId: $labelId
        ));
    }

    /** 'DELETE /api/cards/:cardId/labels/:labelId' */
    public function remove(string $cardId, string $labelId): CardLabelDto
    {
        return $this->client->delete(new CardLabelDeleteAction(
            token: $this->config->getAuthToken(),
            cardId: $cardId,
            labelId: $labelId
        ));
    }
}