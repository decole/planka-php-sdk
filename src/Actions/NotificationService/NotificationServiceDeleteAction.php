<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\NotificationService;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Traits\NotificationServiceHydrateTrait;

final class NotificationServiceDeleteAction implements ActionInterface, ResponseResultInterface
{
    use NotificationServiceHydrateTrait;

    public function __construct(private readonly string $id) {}

    public function url(): string
    {
        return "api/notification-services/{$this->id}";
    }

    public function getOptions(): array
    {
        return [];
    }
}
