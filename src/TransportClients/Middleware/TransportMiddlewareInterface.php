<?php

declare(strict_types=1);

namespace Planka\Bridge\TransportClients\Middleware;

use Planka\Bridge\Contracts\Actions\ActionInterface;

interface TransportMiddlewareInterface
{
    /**
     * Handles transport client requests in pipeline stack.
     *
     * @param ActionInterface                          $action HTTP Action object
     * @param string                                   $method HTTP Method (GET, POST, PATCH, DELETE)
     * @param callable(ActionInterface, string): mixed $next   Next handler in pipeline
     */
    public function handle(ActionInterface $action, string $method, callable $next): mixed;
}
