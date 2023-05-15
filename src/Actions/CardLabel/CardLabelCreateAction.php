<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\CardLabel;

use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Traits\CardLabelHydrateTrait;
use Planka\Bridge\Traits\AuthenticateTrait;

final class CardLabelCreateAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    use AuthenticateTrait, CardLabelHydrateTrait;

    public function __construct(
        private readonly string $cardId,
        private readonly string $labelId,
        string $token
    ) {
        $this->setToken($token);
    }

    public function url(): string
    {
        return "api/cards/{$this->cardId}/labels";
    }

    public function getOptions(): array
    {
        return [
            'body' => [
                'labelId' => $this->labelId,
            ],
        ];
    }
}