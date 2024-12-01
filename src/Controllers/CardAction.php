<?php

declare(strict_types=1);

namespace Planka\Bridge\Controllers;

use Planka\Bridge\Actions\CardAction\CardActionViewAction;
use Planka\Bridge\Views\Dto\Card\CardActionListDto;
use Planka\Bridge\TransportClients\Client;
use Planka\Bridge\Config;

final class CardAction
{
    public function __construct(
        private readonly Config $config,
        private readonly Client $client,
    ) {}

    /** 'GET /api/cards/:cardId/actions' */
    public function getActions(string $cardId): CardActionListDto
    {
        return $this->client->get(new CardActionViewAction(cardId: $cardId, token: $this->config->getAuthToken()));
    }
}
