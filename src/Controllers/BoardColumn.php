<?php

namespace Planka\Bridge\Controllers;

use Planka\Bridge\Config;
use Planka\Bridge\TransportClients\Client;

class BoardColumn
{
    public function __construct(
        private readonly Config $config,
        private readonly Client $client
    ) {
    }

/**
 'POST /api/boards/:boardId/lists': 'lists/create',
'PATCH /api/lists/:id': 'lists/update',
'DELETE /api/lists/:id': 'lists/delete',
 */
}