<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\Webhook;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Traits\WebhookHydrateTrait;

final class WebhookDeleteAction implements ActionInterface, ResponseResultInterface
{
    use WebhookHydrateTrait;

    public function __construct(private readonly string $webhookId) {}

    public function url(): string
    {
        return "api/webhooks/{$this->webhookId}";
    }

    public function getOptions(): array
    {
        return [];
    }
}
