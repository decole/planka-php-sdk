<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\Card;

use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Traits\AuthenticateTrait;
use Planka\Bridge\Traits\CardHydrateTrait;

final class CardCreateAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    use AuthenticateTrait, CardHydrateTrait;

    public function __construct(
        string $token,
        private readonly string $listId,
        private readonly string $name,
        private readonly int $position
    ) {
        $this->setToken($token);
    }

    public function url(): string
    {
        return "api/lists/{$this->listId}/cards";
    }

    public function getOptions(): array
    {
        return [
            'body' => [
                'name' => $this->name,
                'position' => $this->position,
            ],
        ];
    }
}