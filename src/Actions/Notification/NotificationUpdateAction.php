<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\Notification;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Factory\ItemDtoListFactory;
use Planka\Bridge\Views\Factory\Notification\NotificationItemDtoFactory;

final class NotificationUpdateAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    private array $options = [];

    public function __construct(
        private readonly string $notificationId,
        bool $isRead,
    ) {
        $this->options['json'] = ['isRead' => $isRead];
    }

    public function url(): string
    {
        return "api/notifications/{$this->notificationId}";
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getFactory(): OutputInterface
    {
        return new ItemDtoListFactory(new NotificationItemDtoFactory());
    }
}
