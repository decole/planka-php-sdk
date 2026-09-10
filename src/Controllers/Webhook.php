<?php

declare(strict_types=1);

namespace Planka\Bridge\Controllers;

use Planka\Bridge\Actions\Common\CommonPatchAction;
use Planka\Bridge\Actions\Webhook\WebhookCreateAction;
use Planka\Bridge\Actions\Webhook\WebhookDeleteAction;
use Planka\Bridge\Actions\Webhook\WebhookListAction;
use Planka\Bridge\Actions\Webhook\WebhookUpdateAction;
use Planka\Bridge\Builders\WebhookBuilder;
use Planka\Bridge\Contracts\Resources\WebhookResourceInterface;
use Planka\Bridge\Inputs\PatchInputInterface;
use Planka\Bridge\Inputs\PatchInputNormalizer;
use Planka\Bridge\TransportClients\TransportClientInterface;
use Planka\Bridge\Views\Dto\Webhook\WebhookDto;
use Planka\Bridge\Views\Factory\Webhook\WebhookDtoFactory;

final class Webhook implements WebhookResourceInterface
{
    public function __construct(private readonly TransportClientInterface $client) {}

    public function builder(?string $name = null): WebhookBuilder
    {
        return new WebhookBuilder($name);
    }

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
        array|string|null $events = null,
        ?array $excludedEvents = null,
    ): WebhookDto {
        if (\is_string($events)) {
            $events = array_map('trim', explode(',', $events));
        }

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
        array|string|null $events = null,
        ?array $excludedEvents = null,
        array $data = [],
    ): WebhookDto {
        if (null !== $name) {
            $data['name'] = $name;
        }

        if (null !== $url) {
            $data['url'] = $url;
        }

        if (null !== $accessToken) {
            $data['accessToken'] = $accessToken;
        }

        if (null !== $events) {
            if (\is_string($events)) {
                $data['events'] = array_map('trim', explode(',', $events));
            } else {
                $data['events'] = $events;
            }
        }

        if (null !== $excludedEvents) {
            $data['excludedEvents'] = $excludedEvents;
        }

        return $this->client->patch(new WebhookUpdateAction(
            webhookId: $webhookId,
            data: $data,
        ));
    }

    /**
     * 'PATCH /api/webhooks/:id' - Partially updates webhook properties.
     *
     * @param string $webhookId Webhook ID
     *
     * @see Payload structure:
     * array{
     *   name?: string,
     *   url?: string,
     *   accessToken?: string|null,
     *   events?: list<string>|null,
     *   excludedEvents?: list<string>|null
     * }
     */
    public function patching(string $webhookId, array|PatchInputInterface $map): WebhookDto
    {
        return $this->client->patch(new CommonPatchAction(
            urlPath: "api/webhooks/{$webhookId}",
            data: PatchInputNormalizer::normalize($map),
            hydrateCallback: new WebhookDtoFactory(),
        ));
    }

    /** 'DELETE /api/webhooks/:id' */
    public function delete(string $webhookId): WebhookDto
    {
        return $this->client->delete(new WebhookDeleteAction(webhookId: $webhookId));
    }
}
