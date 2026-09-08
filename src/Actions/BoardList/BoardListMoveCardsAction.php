<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\BoardList;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Factory\Board\BoardListDtoFactory;

final class BoardListMoveCardsAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    private array $options = [];

    public function __construct(
        private readonly string $sourceListId,
        string $targetListId,
    ) {
        $this->options['json'] = ['listId' => $targetListId];
    }

    public function url(): string
    {
        return "api/lists/{$this->sourceListId}/move-cards";
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getFactory(): OutputInterface
    {
        return new BoardListDtoFactory();
    }
}
