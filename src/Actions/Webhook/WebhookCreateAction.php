<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\Webhook;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Traits\WebhookHydrateTrait;

final class WebhookCreateAction implements ActionInterface, ResponseResultInterface
{
    use WebhookHydrateTrait;

    private array $options = [];

    public function __construct(
        string $name,
        string $url,
        ?string $accessToken = null,
        ?string $events = null,
        ?string $excludedEvents = null,
    ) {
        $body = [
            'name' => $name,
            'url' => $url,
        ];

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
        return 'api/webhooks';
    }

    public function getOptions(): array
    {
        return $this->options;
    }
}
