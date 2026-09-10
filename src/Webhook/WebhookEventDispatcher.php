<?php

declare(strict_types=1);

namespace Planka\Bridge\Webhook;

use Planka\Bridge\Exceptions\PlankaHydrationException;

final class WebhookEventDispatcher
{
    public function __construct(private readonly WebhookParser $parser = new WebhookParser()) {}

    /**
     * Parses incoming webhook payload and dispatches it to a PSR-14 EventDispatcher or callable listener.
     *
     * @param string|array<string, mixed> $payload    Webhook raw payload or array
     * @param object|callable             $dispatcher PSR-14 EventDispatcherInterface or callable(WebhookEventDto): void
     *
     * @throws PlankaHydrationException
     */
    public function dispatch(string|array $payload, object|callable $dispatcher): WebhookEventDto
    {
        $event = $this->parser->parse($payload);

        if (is_callable($dispatcher)) {
            $dispatcher($event);
        } elseif (method_exists($dispatcher, 'dispatch')) {
            $dispatcher->dispatch($event);
        }

        return $event;
    }
}
