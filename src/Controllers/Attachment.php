<?php

namespace Planka\Bridge\Controllers;

use Planka\Bridge\Config;
use Planka\Bridge\TransportClients\Client;

class Attachment
{
    public function __construct(
        private readonly Config $config,
        private readonly Client $client
    ) {
    }

/**
'POST /api/cards/:cardId/attachments': 'attachments/create',
'PATCH /api/attachments/:id': 'attachments/update',
'DELETE /api/attachments/:id': 'attachments/delete',

'GET /attachments/:id/download/:filename': {
action: 'attachments/download',
skipAssets: false,
},

'GET /attachments/:id/download/thumbnails/cover-256.:extension': {
action: 'attachments/download-thumbnail',
skipAssets: false,
},
 */
}