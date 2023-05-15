<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\Comment;

use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Traits\CommentHydrateTrait;
use Planka\Bridge\Traits\AuthenticateTrait;

final class CommentCreateAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    use AuthenticateTrait, CommentHydrateTrait;

    public function __construct(
        private readonly string $cardId,
        private readonly string $text,
        string $token
    ) {
        $this->setToken($token);
    }

    public function url(): string
    {
        return "api/cards/{$this->cardId}/comment-actions";
    }

    public function getOptions(): array
    {
        return [
            'body' => [
                'text' => $this->text,
            ],
        ];
    }
}