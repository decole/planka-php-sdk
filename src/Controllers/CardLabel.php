<?php

declare(strict_types=1);

namespace Planka\Bridge\Controllers;

use Planka\Bridge\Actions\CardLabel\CardLabelCreateAction;
use Planka\Bridge\Actions\CardLabel\CardLabelDeleteAction;
use Planka\Bridge\Views\Dto\Card\CardLabelDto;
use Planka\Bridge\TransportClients\TransportClientInterface;

final class CardLabel
{
    public function __construct(
        private readonly TransportClientInterface $client,
    ) {}

    /** 'POST /api/cards/:cardId/labels' */
    public function add(string $cardId, string $labelId): CardLabelDto
    {
        return $this->client->post(new CardLabelCreateAction(
            cardId: $cardId,
            labelId: $labelId,
        ));
    }

    /** 'DELETE /api/cards/:cardId/labels/:labelId' */
    public function remove(string $cardId, string $labelId): CardLabelDto
    {
        return $this->client->delete(new CardLabelDeleteAction(
            cardId: $cardId,
            labelId: $labelId,
        ));
    }
}
