<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\Webhook;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Factory\Webhook\WebhookDtoFactory;

final class WebhookUpdateAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    private array $options = [];

    public function __construct(
        private readonly string $webhookId,
        array $data,
    ) {
        $this->options['json'] = $data;
    }

    public function url(): string
    {
        return "api/webhooks/{$this->webhookId}";
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getFactory(): OutputInterface
    {
        return new WebhookDtoFactory();
    }
}
