<?php

namespace Planka\Bridge\Controllers;

use Planka\Bridge\Config;
use Planka\Bridge\TransportClients\Client;

class Card
{
    public function __construct(
        private readonly Config $config,
        private readonly Client $client
    ) {
    }

/**
'POST /api/lists/:listId/cards': 'cards/create',
'GET /api/cards/:id': 'cards/show',
'PATCH /api/cards/:id': 'cards/update',
'DELETE /api/cards/:id': 'cards/delete',
'POST /api/cards/:cardId/memberships': 'card-memberships/create',
'DELETE /api/cards/:cardId/memberships': 'card-memberships/delete',
'POST /api/cards/:cardId/labels': 'card-labels/create',
'DELETE /api/cards/:cardId/labels/:labelId': 'card-labels/delete',
'GET /api/cards/:cardId/actions': 'actions/index',
 */
}