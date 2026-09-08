<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\Board;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Factory\Board\BoardDtoFactory;

final class BoardCreateAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    public function __construct(
        private readonly string $projectId,
        private readonly string $name,
        private readonly int $position,
    ) {}

    public function url(): string
    {
        return "api/projects/{$this->projectId}/boards";
    }

    public function getOptions(): array
    {
        return [
            'json' => [
                'name' => $this->name,
                'position' => $this->position,
            ],
        ];
    }

    public function getFactory(): OutputInterface
    {
        return new BoardDtoFactory();
    }
}
