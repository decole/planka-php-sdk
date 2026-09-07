<?php

declare(strict_types=1);

namespace Planka\Bridge\Controllers;

use Planka\Bridge\Actions\CardMembership\CardMembershipCreateAction;
use Planka\Bridge\Actions\CardMembership\CardMembershipDeleteAction;
use Planka\Bridge\Views\Dto\Card\CardMembershipDto;
use Planka\Bridge\TransportClients\TransportClientInterface;

final class CardMembership
{
    public function __construct(
        private readonly TransportClientInterface $client,
    ) {}

    /** 'POST /api/cards/:cardId/memberships' */
    public function add(string $cardId, string $userId): CardMembershipDto
    {
        return $this->client->post(new CardMembershipCreateAction(
            cardId: $cardId,
            userId: $userId,
        ));
    }

    /** 'DELETE /api/cards/:cardId/memberships?userId=:userId' */
    public function remove(string $cardId, string $userId): CardMembershipDto
    {
        return $this->client->delete(new CardMembershipDeleteAction(
            cardId: $cardId,
            userId: $userId,
        ));
    }
}
