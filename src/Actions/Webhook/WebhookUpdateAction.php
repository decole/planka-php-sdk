<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\Webhook;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Traits\WebhookHydrateTrait;

final class WebhookUpdateAction implements ActionInterface, ResponseResultInterface
{
    use WebhookHydrateTrait;

    private array $options = [];

    public function __construct(
        private readonly string $webhookId,
        ?string $name = null,
        ?string $url = null,
        ?string $accessToken = null,
        ?string $events = null,
        ?string $excludedEvents = null,
    ) {
        $body = [];

        if (null !== $name) {
            $body['name'] = $name;
        }

        if (null !== $url) {
            $body['url'] = $url;
        }

        if (null !== $accessToken) {
            $body['accessToken'] = $accessToken;
        }

        if (null !== $events) {
            $body['events'] = $events;
        }

        if (null !== $excludedEvents) {
            $body['excludedEvents'] = $excludedEvents;
        }

        $this->options['json'] = $body;
    }

    public function url(): string
    {
        return "api/webhooks/{$this->webhookId}";
    }

    public function getOptions(): array
    {
        return $this->options;
    }
}
