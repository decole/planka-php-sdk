<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\User;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Factory\User\UserDtoFactory;

final class UserUpdatePasswordAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    public function __construct(
        private readonly string $userId,
        private readonly string $password,
        private readonly string $currentPassword,
    ) {}

    public function url(): string
    {
        return "api/users/{$this->userId}/password";
    }

    public function getOptions(): array
    {
        return [
            'json' => [
                'password' => $this->password,
                'currentPassword' => $this->currentPassword,
            ],
        ];
    }

    public function getFactory(): OutputInterface
    {
        return new UserDtoFactory();
    }
}
