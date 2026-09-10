<?php

declare(strict_types=1);

namespace Planka\Bridge\Contracts\Resources;

use Planka\Bridge\Builders\WebhookBuilder;
use Planka\Bridge\Inputs\PatchInputInterface;
use Planka\Bridge\Inputs\WebhookPatchInput;
use Planka\Bridge\Views\Dto\Webhook\WebhookDto;

interface WebhookResourceInterface
{
    public function builder(?string $name = null): WebhookBuilder;

    /**
     * @return list<WebhookDto>
     */
    public function list(): array;

    /**
     * @param array<string>|string|null $events
     * @param array<string>|null        $excludedEvents
     */
    public function create(
        string $name,
        string $url,
        ?string $accessToken = null,
        array|string|null $events = null,
        ?array $excludedEvents = null,
    ): WebhookDto;

    /**
     * @param array<string>|string|null $events
     * @param array<string>|null        $excludedEvents
     * @param array<string, mixed>      $data
     */
    public function update(
        string $webhookId,
        ?string $name = null,
        ?string $url = null,
        ?string $accessToken = null,
        array|string|null $events = null,
        ?array $excludedEvents = null,
        array $data = [],
    ): WebhookDto;

    /**
     * @param array{
     *   name?: string,
     *   url?: string,
     *   accessToken?: string|null,
     *   events?: list<string>|null,
     *   excludedEvents?: list<string>|null
     * }|WebhookPatchInput|PatchInputInterface $map
     */
    public function patching(string $webhookId, array|PatchInputInterface $map): WebhookDto;

    public function delete(string $webhookId): WebhookDto;
}
