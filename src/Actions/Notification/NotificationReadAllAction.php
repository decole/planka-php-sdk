<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\Notification;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Factory\Notification\NotificationListDtoFactory;

final class NotificationReadAllAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    public function url(): string
    {
        return 'api/notifications/read-all';
    }

    public function getOptions(): array
    {
        return [];
    }

    public function getFactory(): OutputInterface
    {
        return new NotificationListDtoFactory();
    }
}
