<?php

declare(strict_types=1);

namespace Planka\Bridge\Builders;

use Planka\Bridge\Inputs\WebhookPatchInput;

final class WebhookBuilder
{
    private ?string $name = null;

    private ?string $url = null;

    private ?string $accessToken = null;

    /** @var list<string>|null */
    private ?array $events = null;

    /** @var list<string>|null */
    private ?array $excludedEvents = null;

    public function __construct(?string $name = null)
    {
        if (null !== $name) {
            $this->name = $name;
        }
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function setAccessToken(?string $accessToken): self
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * @param list<string>|null $events
     */
    public function setEvents(?array $events): self
    {
        $this->events = $events;

        return $this;
    }

    /**
     * @param list<string>|null $excludedEvents
     */
    public function setExcludedEvents(?array $excludedEvents): self
    {
        $this->excludedEvents = $excludedEvents;

        return $this;
    }

    public function build(): WebhookPatchInput
    {
        return new WebhookPatchInput(
            name: $this->name,
            url: $this->url,
            accessToken: $this->accessToken,
            events: $this->events,
            excludedEvents: $this->excludedEvents,
        );
    }
}
