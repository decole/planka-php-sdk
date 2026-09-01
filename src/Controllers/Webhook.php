<?php

declare(strict_types=1);

namespace Planka\Bridge\Controllers;

use Planka\Bridge\Actions\Webhook\WebhookCreateAction;
use Planka\Bridge\Actions\Webhook\WebhookDeleteAction;
use Planka\Bridge\Actions\Webhook\WebhookListAction;
use Planka\Bridge\Actions\Webhook\WebhookUpdateAction;
use Planka\Bridge\Config;
use Planka\Bridge\TransportClients\Client;
use Planka\Bridge\Views\Dto\Webhook\WebhookDto;

final class Webhook
{
    public function __construct(
        private readonly Config $config,
        private readonly Client $client,
    ) {}

    /**
     * 'GET /api/webhooks'.
     *
     * @return list<WebhookDto>
     */
    public function list(): array
    {
        return $this->client->get(new WebhookListAction());
    }

    /** 'POST /api/webhooks' */
    public function create(
        string $name,
        string $url,
        ?string $accessToken = null,
        ?string $events = null,
        ?string $excludedEvents = null,
    ): WebhookDto {
        return $this->client->post(new WebhookCreateAction(
            name: $name,
            url: $url,
            accessToken: $accessToken,
            events: $events,
            excludedEvents: $excludedEvents,
        ));
    }

    /** 'PATCH /api/webhooks/:id' */
    public function update(
        string $webhookId,
        ?string $name = null,
        ?string $url = null,
        ?string $accessToken = null,
        ?string $events = null,
        ?string $excludedEvents = null,
    ): WebhookDto {
        return $this->client->patch(new WebhookUpdateAction(
            webhookId: $webhookId,
            name: $name,
            url: $url,
            accessToken: $accessToken,
            events: $events,
            excludedEvents: $excludedEvents,
        ));
    }

    /** 'DELETE /api/webhooks/:id' */
    public function delete(string $webhookId): WebhookDto
    {
        return $this->client->delete(new WebhookDeleteAction(webhookId: $webhookId));
    }
}
