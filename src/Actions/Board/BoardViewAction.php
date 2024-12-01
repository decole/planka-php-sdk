<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\Board;

use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Traits\AuthenticateTrait;
use Planka\Bridge\Traits\BoardHydrateTrait;

final class BoardViewAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    use AuthenticateTrait;
    use BoardHydrateTrait;

    public function __construct(private readonly string $boardId, string $token)
    {
        $this->setToken($token);
    }

    public function url(): string
    {
        return "api/boards/{$this->boardId}";
    }

    public function getOptions(): array
    {
        return [];
    }
}
