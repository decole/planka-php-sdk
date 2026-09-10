<?php

declare(strict_types=1);

namespace Planka\Bridge\Contracts\Resources;

use Planka\Bridge\Views\Dto\Comment\CommentDto;

interface CommentResourceInterface
{
    /**
     * @return list<CommentDto>
     */
    public function list(string $cardId): array;

    public function add(string $cardId, string $text): CommentDto;

    public function update(string $commentId, string $text): CommentDto;

    /**
     * @param array{text?: string} $map
     */
    public function patching(string $commentId, array $map): CommentDto;

    public function remove(string $commentId): CommentDto;
}
