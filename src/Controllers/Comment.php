<?php

namespace Planka\Bridge\Controllers;

use Planka\Bridge\Config;
use Planka\Bridge\TransportClients\Client;

class Comment
{
    public function __construct(
        private readonly Config $config,
        private readonly Client $client
    ) {
    }

/**
'POST /api/cards/:cardId/comment-actions': 'comment-actions/create',
'PATCH /api/comment-actions/:id': 'comment-actions/update',
'DELETE /api/comment-actions/:id': 'comment-actions/delete',
 */
}