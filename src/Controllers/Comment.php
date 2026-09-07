<?php

declare(strict_types=1);

namespace Planka\Bridge\Controllers;

use Planka\Bridge\Actions\Comment\CommentCreateAction;
use Planka\Bridge\Actions\Comment\CommentDeleteAction;
use Planka\Bridge\Actions\Comment\CommentUpdateAction;
use Planka\Bridge\Actions\Common\CommonPatchAction;
use Planka\Bridge\Config;
use Planka\Bridge\Traits\CommentHydrateTrait;
use Planka\Bridge\TransportClients\Client;
use Planka\Bridge\Views\Dto\Comment\CommentDto;

final class Comment
{
    use CommentHydrateTrait;

    public function __construct(
        private readonly Config $config,
        private readonly Client $client,
    ) {}

    /**
     * 'GET /api/cards/:cardId/comments'.
     *
     * @return list<CommentDto>
     */
    public function list(string $cardId): array
    {
        return $this->client->get(new \Planka\Bridge\Actions\Comment\CommentListAction(
            cardId: $cardId,
            token: $this->config->getAuthToken(),
        ));
    }

    /** 'POST /api/cards/:cardId/comments' */
    public function add(string $cardId, string $text): CommentDto
    {
        return $this->client->post(new CommentCreateAction(
            cardId: $cardId,
            text: $text,
            token: $this->config->getAuthToken(),
        ));
    }

    /** 'PATCH /api/comment-actions/:id' */
    public function update(string $commentId, string $text): CommentDto
    {
        return $this->client->patch(new CommentUpdateAction(
            commentId: $commentId,
            text: $text,
            token: $this->config->getAuthToken(),
        ));
    }

    /**
     * 'PATCH /api/comment-actions/:id' - Partially updates comment properties.
     *
     * @param string $commentId Comment ID
     * @param array{
     *   text?: string
     * } $map Associative array of fields to update
     */
    public function patching(string $commentId, array $map): CommentDto
    {
        return $this->client->patch(new CommonPatchAction(
            urlPath: "api/comment-actions/{$commentId}",
            data: $map,
            hydrateCallback: fn($response) => $this->hydrate($response),
        ));
    }

    /** 'DELETE /api/comment-actions/:id' */
    public function remove(string $commentId): CommentDto
    {
        return $this->client->delete(new CommentDeleteAction(
            commentId: $commentId,
            token: $this->config->getAuthToken(),
        ));
    }
}
