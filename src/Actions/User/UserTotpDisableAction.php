<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\User;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Factory\User\UserDtoFactory;

final class UserTotpDisableAction implements ActionInterface, ResponseResultInterface
{
    public function __construct(
        private readonly string $userId,
        private readonly ?string $currentPassword = null,
        private readonly ?string $code = null,
    ) {}

    public function url(): string
    {
        return "api/users/{$this->userId}/totp";
    }

    public function getOptions(): array
    {
        $json = [];

        if (null !== $this->currentPassword) {
            $json['currentPassword'] = $this->currentPassword;
        }

        if (null !== $this->code) {
            $json['code'] = $this->code;
        }

        return [
            'json' => $json,
        ];
    }

    public function getFactory(): OutputInterface
    {
        return new UserDtoFactory();
    }
}
