<?php

namespace Planka\Bridge\Controllers;

use Planka\Bridge\Actions\BoardMembership\BoardMembershipCreateAction;
use Planka\Bridge\Actions\BoardMembership\BoardMembershipDeleteAction;
use Planka\Bridge\Actions\BoardMembership\BoardMembershipUpdateAction;
use Planka\Bridge\Config;
use Planka\Bridge\TransportClients\Client;

class BoardMembership
{
    public function __construct(
        private readonly Config $config,
        private readonly Client $client
    ) {
    }

    /** 'POST /api/boards/:boardId/memberships' */
    public function createMembership()
    {
        return $this->client->post(new BoardMembershipCreateAction(token: $this->config->getAuthToken()));
    }

    /** 'PATCH /api/board-memberships/:id' */
    public function updateMembership()
    {
        return $this->client->patch(new BoardMembershipUpdateAction(token: $this->config->getAuthToken()));
    }

    /** 'DELETE /api/board-memberships/:id' */
    public function deleteMembership()
    {
        return $this->client->delete(new BoardMembershipDeleteAction(token: $this->config->getAuthToken()));
    }
}