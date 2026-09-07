<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\CardLabel;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Factory\Card\CardLabelDtoFactory;

final class CardLabelCreateAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    public function __construct(
        private readonly string $cardId,
        private readonly string $labelId,
    ) {}

    public function url(): string
    {
        return "api/cards/{$this->cardId}/labels";
    }

    public function getOptions(): array
    {
        return [
            'json' => [
                'labelId' => $this->labelId,
            ],
        ];
    }

    public function getFactory(): OutputInterface
    {
        return new CardLabelDtoFactory();
    }
}
