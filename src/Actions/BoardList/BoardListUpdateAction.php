<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\BoardList;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Factory\Board\BoardListDtoFactory;

final class BoardListUpdateAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    public function __construct(
        private readonly string $listId,
        private readonly string $name,
    ) {}

    public function url(): string
    {
        return "api/lists/{$this->listId}";
    }

    public function getOptions(): array
    {
        return [
            'json' => [
                'name' => $this->name,
            ],
        ];
    }

    public function getFactory(): OutputInterface
    {
        return new BoardListDtoFactory();
    }
}
