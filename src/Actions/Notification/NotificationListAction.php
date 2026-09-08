<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\Notification;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Factory\Notification\NotificationListDtoFactory;

final class NotificationListAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    private array $options = [];

    public function __construct(
        int $limit = 30,
        ?string $nextId = null,
    ) {
        $query = ['limit' => $limit];

        if (null !== $nextId) {
            $query['nextId'] = $nextId;
        }

        $this->options['query'] = $query;
    }

    public function url(): string
    {
        return 'api/notifications';
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getFactory(): OutputInterface
    {
        return new NotificationListDtoFactory();
    }
}
