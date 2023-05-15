<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\BoardMembership;

use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Traits\BoardMembershipHydrateTrait;
use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Enum\BoardMembershipRoleEnum;
use Planka\Bridge\Traits\AuthenticateTrait;

final class BoardMembershipAddAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    use AuthenticateTrait, BoardMembershipHydrateTrait;

    public function __construct(
        private readonly string $boardId,
        private readonly string $userId,
        private readonly BoardMembershipRoleEnum $role,
        string $token
    ) {
        $this->setToken($token);
    }

    public function url(): string
    {
        return "api/boards/{$this->boardId}/memberships";
    }

    public function getOptions(): array
    {
        return [
            'body' => [
                'role' => $this->role->value,
                'userId' => $this->userId,
            ],
        ];
    }
}