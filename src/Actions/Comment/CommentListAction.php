<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\Comment;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Factory\Comment\CommentDtoFactory;
use Planka\Bridge\Views\Factory\ItemDtoListFactory;

final class CommentListAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    public function __construct(private readonly string $cardId) {}

    public function url(): string
    {
        return "api/cards/{$this->cardId}/comments";
    }

    public function getOptions(): array
    {
        return [];
    }

    public function getFactory(): OutputInterface
    {
        return new ItemDtoListFactory(new CommentDtoFactory());
    }
}
