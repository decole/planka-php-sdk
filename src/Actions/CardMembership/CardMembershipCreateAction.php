<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\CardMembership;

use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Traits\CardMembershipHydrateTrait;
use Planka\Bridge\Traits\AuthenticateTrait;

final class CardMembershipCreateAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    use AuthenticateTrait, CardMembershipHydrateTrait;

    public function __construct(
        string $token,
        private readonly string $cardId,
        private readonly string $userId
    ) {
        $this->setToken($token);
    }

    public function url(): string
    {
        return "api/cards/{$this->cardId}/memberships";
    }

    public function getOptions(): array
    {
        return [
            'body' => [
                'userId' => $this->userId,
            ],
        ];
    }
}