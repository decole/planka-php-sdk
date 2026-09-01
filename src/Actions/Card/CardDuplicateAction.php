<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\Card;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Traits\CardHydrateTrait;

final class CardDuplicateAction implements ActionInterface, ResponseResultInterface
{
    use CardHydrateTrait;

    public function __construct(private readonly string $cardId) {}

    public function url(): string
    {
        return "api/cards/{$this->cardId}/duplicate";
    }

    public function getOptions(): array
    {
        return [];
    }
}
