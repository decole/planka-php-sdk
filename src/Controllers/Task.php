<?php

namespace Planka\Bridge\Controllers;

use Planka\Bridge\Config;
use Planka\Bridge\TransportClients\Client;

class Task
{
    public function __construct(
        private readonly Config $config,
        private readonly Client $client
    ) {
    }
/**
'POST /api/cards/:cardId/tasks': 'tasks/create',
'PATCH /api/tasks/:id': 'tasks/update',
'DELETE /api/tasks/:id': 'tasks/delete',
 */
}