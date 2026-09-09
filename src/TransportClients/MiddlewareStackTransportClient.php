<?php

declare(strict_types=1);

namespace Planka\Bridge\TransportClients;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\TransportClients\Middleware\TransportMiddlewareInterface;

final class MiddlewareStackTransportClient implements TransportClientInterface
{
    /**
     * @param TransportMiddlewareInterface[] $middlewares
     */
    public function __construct(
        private readonly TransportClientInterface $innerClient,
        private readonly array $middlewares = [],
    ) {}

    public function get(ActionInterface $action): mixed
    {
        return $this->execute($action, 'GET');
    }

    public function post(ActionInterface $action): mixed
    {
        return $this->execute($action, 'POST');
    }

    public function patch(ActionInterface $action): mixed
    {
        return $this->execute($action, 'PATCH');
    }

    public function delete(ActionInterface $action): mixed
    {
        return $this->execute($action, 'DELETE');
    }

    private function execute(ActionInterface $action, string $method): mixed
    {
        $pipeline = array_reduce(
            array_reverse($this->middlewares),
            fn (callable $next, TransportMiddlewareInterface $middleware) => fn (ActionInterface $act, string $m) => $middleware->handle($act, $m, $next),
            fn (ActionInterface $act, string $m) => match ($m) {
                'GET' => $this->innerClient->get($act),
                'POST' => $this->innerClient->post($act),
                'PATCH' => $this->innerClient->patch($act),
                'DELETE' => $this->innerClient->delete($act),
                default => throw new \InvalidArgumentException("Unsupported HTTP method: {$m}"),
            },
        );

        return $pipeline($action, $method);
    }
}
