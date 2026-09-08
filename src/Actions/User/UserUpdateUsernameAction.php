<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\User;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Factory\User\UserDtoFactory;

final class UserUpdateUsernameAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    public function __construct(
        private readonly string $userId,
        private readonly string $username,
    ) {}

    public function url(): string
    {
        return "api/users/{$this->userId}/username";
    }

    public function getOptions(): array
    {
        return [
            'json' => [
                'username' => $this->username,
            ],
        ];
    }

    public function getFactory(): OutputInterface
    {
        return new UserDtoFactory();
    }
}
