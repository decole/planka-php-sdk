<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\BoardMembership;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Enum\BoardMembershipRoleEnum;
use Planka\Bridge\Views\Factory\Board\BoardMembershipDtoFactory;

final class BoardMembershipAddAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    public function __construct(
        private readonly string $boardId,
        private readonly string $userId,
        private readonly BoardMembershipRoleEnum $role,
    ) {}

    public function url(): string
    {
        return "api/boards/{$this->boardId}/memberships";
    }

    public function getOptions(): array
    {
        return [
            'json' => [
                'userId' => $this->userId,
                'role' => $this->role->value,
            ],
        ];
    }

    public function getFactory(): OutputInterface
    {
        return new BoardMembershipDtoFactory();
    }
}
