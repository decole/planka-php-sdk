<?php

declare(strict_types=1);

namespace Planka\Bridge\Controllers;

use Planka\Bridge\Actions\CardMembership\CardMembershipCreateAction;
use Planka\Bridge\Actions\CardMembership\CardMembershipDeleteAction;
use Planka\Bridge\Views\Dto\Board\BoardDto;

class CardMembership
{
    // todo fix it
    /** 'POST /api/cards/:cardId/memberships' */
    public function create(string $projectId, string $name, int $position): BoardDto
    {
        return $this->client->post(new CardMembershipCreateAction(
            token: $this->config->getAuthToken(),
        ));
    }

    /** 'DELETE /api/cards/:cardId/memberships' */
    public function delete(string $listId): BoardDto
    {
        return $this->client->delete(new CardMembershipDeleteAction(token: $this->config->getAuthToken(), listId: $listId));
    }
}