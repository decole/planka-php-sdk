<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\Board;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Factory\Board\BoardDtoFactory;

final class BoardUpdateAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    public function __construct(
        private readonly string $boardId,
        private readonly array $data,
    ) {}

    public function url(): string
    {
        return "api/boards/{$this->boardId}";
    }

    public function getOptions(): array
    {
        return [
            'json' => $this->data,
        ];
    }

    public function getFactory(): OutputInterface
    {
        return new BoardDtoFactory();
    }
}
