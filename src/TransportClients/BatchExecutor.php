<?php

declare(strict_types=1);

namespace Planka\Bridge\TransportClients;

use Planka\Bridge\Contracts\Actions\ActionInterface;

final class BatchExecutor
{
    public function __construct(private readonly TransportClientInterface $client) {}

    /**
     * Executes multiple actions in batch, returning an associative array of results matching action keys.
     *
     * @param array<string|int, ActionInterface> $actions
     * @param string                             $method  Default HTTP method (GET, POST, etc.)
     *
     * @return array<string|int, mixed>
     */
    public function execute(array $actions, string $method = 'GET'): array
    {
        $results = [];

        foreach ($actions as $key => $action) {
            $results[$key] = match (strtoupper($method)) {
                'GET' => $this->client->get($action),
                'POST' => $this->client->post($action),
                'PATCH' => $this->client->patch($action),
                'DELETE' => $this->client->delete($action),
                default => throw new \InvalidArgumentException("Unsupported HTTP method in batch: {$method}"),
            };
        }

        return $results;
    }
}
