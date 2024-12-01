<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\Card;

use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Traits\AuthenticateTrait;

final class CardDeleteAction implements ActionInterface, AuthenticateInterface
{
    use AuthenticateTrait;

    public function __construct(private readonly string $cardId, string $token)
    {
        $this->setToken($token);
    }

    public function url(): string
    {
        return "api/cards/{$this->cardId}";
    }

    public function getOptions(): array
    {
        return [];
    }
}
