<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\BoardMembership;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Factory\Board\BoardMembershipDtoFactory;

final class BoardMembershipDeleteAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    public function __construct(private readonly string $boardMembershipId) {}

    public function url(): string
    {
        return "api/board-memberships/{$this->boardMembershipId}";
    }

    public function getOptions(): array
    {
        return [];
    }

    public function getFactory(): OutputInterface
    {
        return new BoardMembershipDtoFactory();
    }
}
