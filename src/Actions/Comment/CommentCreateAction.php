<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\Comment;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Factory\Comment\CommentDtoFactory;

final class CommentCreateAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    public function __construct(
        private readonly string $cardId,
        private readonly string $text,
    ) {}

    public function url(): string
    {
        return "api/cards/{$this->cardId}/comments";
    }

    public function getOptions(): array
    {
        return [
            'json' => [
                'text' => $this->text,
            ],
        ];
    }

    public function getFactory(): OutputInterface
    {
        return new CommentDtoFactory();
    }
}
