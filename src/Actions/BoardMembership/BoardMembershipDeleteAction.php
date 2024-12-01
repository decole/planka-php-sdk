<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\BoardMembership;

use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Traits\BoardMembershipHydrateTrait;
use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Traits\AuthenticateTrait;

final class BoardMembershipDeleteAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    use AuthenticateTrait;
    use BoardMembershipHydrateTrait;

    public function __construct(private readonly string $membership, string $token)
    {
        $this->setToken($token);
    }

    public function url(): string
    {
        return "api/board-memberships/{$this->membership}";
    }

    public function getOptions(): array
    {
        return [
            'body' => [],
        ];
    }
}
