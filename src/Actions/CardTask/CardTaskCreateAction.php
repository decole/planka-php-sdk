<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\CardTask;

use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Traits\CardTaskHydrateTrait;
use Planka\Bridge\Traits\AuthenticateTrait;

final class CardTaskCreateAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    use AuthenticateTrait;
    use CardTaskHydrateTrait;

    public function __construct(
        private readonly string $cardId,
        private readonly string $name,
        private readonly int $position,
        string $token,
    ) {
        $this->setToken($token);
    }

    public function url(): string
    {
        return "api/cards/{$this->cardId}/tasks";
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
