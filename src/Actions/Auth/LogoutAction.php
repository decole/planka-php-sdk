<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\Auth;

use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Traits\AuthenticateTrait;

final class LogoutAction implements ActionInterface, AuthenticateInterface
{
    use AuthenticateTrait;

    public function __construct(string $token)
    {
        $this->setToken($token);
    }

    public function url(): string
    {
        return 'api/access-tokens/me';
    }

    public function getOptions(): array
    {
        return [];
    }
}