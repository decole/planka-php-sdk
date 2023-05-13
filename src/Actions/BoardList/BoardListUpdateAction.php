<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\BoardList;

use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Traits\BoardListHydrateTrait;
use Planka\Bridge\Traits\AuthenticateTrait;

final class BoardListUpdateAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    use AuthenticateTrait, BoardListHydrateTrait;

    public function __construct(
        string $token,
        private readonly string $listId,
        private readonly string $name
    ) {
        $this->setToken($token);
    }

    public function url(): string
    {
        return "api/lists/{$this->listId}";
    }

    public function getOptions(): array
    {
        return [
            'body' => [
                'name' => $this->name,
            ],
        ];
    }
}