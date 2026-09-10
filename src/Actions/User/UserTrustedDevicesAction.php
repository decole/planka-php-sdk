<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\User;

use Planka\Bridge\Contracts\Actions\ActionInterface;

final class UserTrustedDevicesAction implements ActionInterface
{
    public function __construct(private readonly string $userId) {}

    public function url(): string
    {
        return "api/users/{$this->userId}/trusted-devices";
    }

    public function getOptions(): array
    {
        return [];
    }
}
