<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\User;

use Planka\Bridge\Contracts\Actions\ActionInterface;

final class UserTrustedDeviceDeleteAction implements ActionInterface
{
    public function __construct(
        private readonly string $userId,
        private readonly string $deviceId,
    ) {}

    public function url(): string
    {
        return "api/users/{$this->userId}/trusted-devices/{$this->deviceId}";
    }

    public function getOptions(): array
    {
        return [];
    }
}
