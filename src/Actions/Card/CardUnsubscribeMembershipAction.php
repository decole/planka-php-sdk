<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\Card;

use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Traits\CardMembershipHydrateTrait;
use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Traits\AuthenticateTrait;

final class CardUnsubscribeMembershipAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    use AuthenticateTrait, CardMembershipHydrateTrait;

    public function __construct(
        private readonly string $cardId,
        private readonly string $userId,
        string $token
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
