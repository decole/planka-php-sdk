<?php

declare(strict_types=1);

namespace Planka\Bridge\Controllers;

use Planka\Bridge\Actions\BoardList\BoardListCreateAction;
use Planka\Bridge\Actions\BoardList\BoardListDeleteAction;
use Planka\Bridge\Actions\BoardList\BoardListUpdateAction;
use Planka\Bridge\Config;
use Planka\Bridge\TransportClients\Client;
use Planka\Bridge\Views\Dto\Board\BoardDto;

class Attachment
{
    public function __construct(
        private readonly Config $config,
        private readonly Client $client
    ) {
    }

    // fix actions

    /** 'POST /api/cards/:cardId/attachments'*/
    public function create(string $projectId, string $name, int $position): BoardDto
    {
        return $this->client->post(new BoardListCreateAction(
            token: $this->config->getAuthToken(),
        ));
    }

    /** 'PATCH /api/attachments/:id' */
    public function update(string $listId, string $name): BoardDto
    {
        return $this->client->patch(new BoardListUpdateAction(
            token: $this->config->getAuthToken(),
            listId: $listId
        ));
    }

    /** 'DELETE /api/attachments/:id' */
    public function delete(string $listId): BoardDto
    {
        return $this->client->delete(new BoardListDeleteAction(token: $this->config->getAuthToken(), listId: $listId));
    }

    /** 'GET /attachments/:id/download/:filename' */
    public function downloadFile()
    {

    }

    /** 'GET /attachments/:id/download/thumbnails/cover-256.:extension' */
    public function downloadThumbnail()
    {

    }
}