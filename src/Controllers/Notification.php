<?php

namespace Planka\Bridge\Controllers;

use Planka\Bridge\Config;
use Planka\Bridge\TransportClients\Client;

class Notification
{
    public function __construct(
        private readonly Config $config,
        private readonly Client $client
    ) {
    }

/**
'GET /api/notifications': 'notifications/index',
'GET /api/notifications/:id': 'notifications/show',
'PATCH /api/notifications/:ids': 'notifications/update',
 */
}