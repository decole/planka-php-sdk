<?php

namespace Planka\Bridge\Controllers;

use Planka\Bridge\Config;
use Planka\Bridge\TransportClients\Client;

class Label
{
    public function __construct(
        private readonly Config $config,
        private readonly Client $client
    ) {
    }

    /**
    'POST /api/boards/:boardId/labels': 'labels/create',
    'PATCH /api/labels/:id': 'labels/update',
    'DELETE /api/labels/:id': 'labels/delete',
     */
}