<?php

namespace Planka\Bridge\Actions\User;

use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Traits\AuthenticateTrait;
use Planka\Bridge\Traits\UserHydrateTrait;

final class UserViewAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    use AuthenticateTrait, UserHydrateTrait;

    public function __construct(string $token, private readonly string $id)
    {
        $this->setToken($token);
    }

    public function url(): string
    {
        return "api/users/{$this->id}";
    }

    public function getOptions(): array
    {
        return [];
    }
}