<?php

declare(strict_types=1);

namespace Planka\Bridge\Inputs;

final class WebhookPatchInput implements PatchInputInterface
{
    /**
     * @param list<string>|null $events
     * @param list<string>|null $excludedEvents
     */
    public function __construct(
        public readonly ?string $name = null,
        public readonly ?string $url = null,
        public readonly ?string $accessToken = null,
        public readonly ?array $events = null,
        public readonly ?array $excludedEvents = null,
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'url' => $this->url,
            'accessToken' => $this->accessToken,
            'events' => $this->events,
            'excludedEvents' => $this->excludedEvents,
        ], static fn ($v) => null !== $v);
    }
}
