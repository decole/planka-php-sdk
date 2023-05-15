<?php

declare(strict_types=1);

namespace Planka\Bridge\Controllers;

use Planka\Bridge\Actions\Comment\CommentCreateAction;
use Planka\Bridge\Actions\Comment\CommentDeleteAction;
use Planka\Bridge\Actions\Comment\CommentUpdateAction;
use Planka\Bridge\Views\Dto\Comment\CommentDto;
use Planka\Bridge\TransportClients\Client;
use Planka\Bridge\Config;

final class Comment
{
    public function __construct(
        private readonly Config $config,
        private readonly Client $client
    ) {
    }

    /** 'POST /api/cards/:cardId/comment-actions' */
    public function add(string $cardId, string $text): CommentDto
    {
        return $this->client->post(new CommentCreateAction(
            cardId: $cardId,
            text: $text,
            token: $this->config->getAuthToken()
        ));
    }

    /** 'PATCH /api/comment-actions/:id' */
    public function update(string $commentId, string $text): CommentDto
    {
        return $this->client->patch(new CommentUpdateAction(
            commentId: $commentId,
            text: $text,
            token: $this->config->getAuthToken()
        ));
    }

    /** 'DELETE /api/comment-actions/:id' */
    public function remove(string $commentId): CommentDto
    {
        return $this->client->delete(new CommentDeleteAction(
            commentId: $commentId,
            token: $this->config->getAuthToken()
        ));
    }
}