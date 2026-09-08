<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\CardMembership;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Factory\Card\CardMembershipDtoFactory;

final class CardMembershipCreateAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    public function __construct(
        private readonly string $cardId,
        private readonly string $userId,
    ) {}

    public function url(): string
    {
        return "api/cards/{$this->cardId}/memberships";
    }

    public function getOptions(): array
    {
        return [
            'json' => [
                'userId' => $this->userId,
            ],
        ];
    }

    public function getFactory(): OutputInterface
    {
        return new CardMembershipDtoFactory();
    }
}
