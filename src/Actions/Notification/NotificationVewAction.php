<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\Notification;

use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Traits\NotificationHydrateTrait;
use Planka\Bridge\Traits\AuthenticateTrait;

final class NotificationVewAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    use AuthenticateTrait;
    use NotificationHydrateTrait;

    public function __construct(private readonly string $notifyId, string $token)
    {
        $this->setToken($token);
    }

    public function url(): string
    {
        return "api/notifications/{$this->notifyId}";
    }

    public function getOptions(): array
    {
        return [];
    }
}
