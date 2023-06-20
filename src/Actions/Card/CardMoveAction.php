<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\Card;

use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Traits\AuthenticateTrait;
use Planka\Bridge\Traits\CardHydrateTrait;
use Planka\Bridge\Views\Dto\Card\CardDto;

final class CardMoveAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    use AuthenticateTrait, CardHydrateTrait;

    public function __construct(private readonly CardDto $card, string $token)
    {
        $this->setToken($token);
    }

    public function url(): string
    {
        return "api/cards/{$this->card->id}";
    }

    public function getOptions(): array
    {
        return [
            'json' => [
                'listId' => $this->card->listId,
                'boardId' => $this->card->boardId,
                'position' => $this->card->position,
            ],
        ];
    }
}