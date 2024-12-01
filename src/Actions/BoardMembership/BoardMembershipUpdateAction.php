<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\BoardMembership;

use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Traits\BoardMembershipHydrateTrait;
use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Enum\BoardMembershipRoleEnum;
use Planka\Bridge\Traits\AuthenticateTrait;

final class BoardMembershipUpdateAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    use AuthenticateTrait;
    use BoardMembershipHydrateTrait;

    public function __construct(
        private readonly string $membershipId,
        private readonly BoardMembershipRoleEnum $role,
        string $token,
        private readonly bool $canComment = true,
    ) {
        $this->setToken($token);
    }

    public function url(): string
    {
        return "api/board-memberships/{$this->membershipId}";
    }

    public function getOptions(): array
    {
        return [
            'body' => [
                'canComment' => $this->canComment,
                'role' => $this->role->value,
            ],
        ];
    }
}
