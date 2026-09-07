<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\Card;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Factory\Card\CardDtoFactory;

final class CardUpdateAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    public function __construct(
        private readonly string $cardId,
        private readonly array $data,
    ) {}

    public function url(): string
    {
        return "api/cards/{$this->cardId}";
    }

    public function getOptions(): array
    {
        return [
            'json' => $this->data,
        ];
    }

    public function getFactory(): OutputInterface
    {
        return new CardDtoFactory();
    }
}
