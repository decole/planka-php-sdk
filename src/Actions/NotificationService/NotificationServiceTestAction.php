<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\NotificationService;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Factory\Common\TestResultDtoFactory;

final class NotificationServiceTestAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    private array $options = [];

    public function __construct(
        private readonly string $serviceId,
        ?string $cardId = null,
    ) {
        if (null !== $cardId) {
            $this->options['json'] = ['cardId' => $cardId];
        }
    }

    public function url(): string
    {
        return "api/notification-services/{$this->serviceId}/test";
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getFactory(): OutputInterface
    {
        return new TestResultDtoFactory();
    }
}
