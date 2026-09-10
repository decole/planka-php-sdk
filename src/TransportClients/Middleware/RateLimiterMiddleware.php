<?php

declare(strict_types=1);

namespace Planka\Bridge\TransportClients\Middleware;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Exceptions\ResponseException;

final class RateLimiterMiddleware implements TransportMiddlewareInterface
{
    /**
     * @param int $maxWaitSeconds Maximum seconds to wait on 429 Retry-After before throwing exception (default: 60s)
     */
    public function __construct(
        private readonly int $maxWaitSeconds = 60,
    ) {}

    /**
     * @throws ResponseException
     */
    public function handle(ActionInterface $action, string $method, callable $next): mixed
    {
        while (true) {
            try {
                return $next($action, $method);
            } catch (ResponseException $e) {
                if (429 !== $e->getStatusCode()) {
                    throw $e;
                }

                $retryAfterSeconds = $this->extractRetryAfter($e);

                if (null === $retryAfterSeconds || $retryAfterSeconds > $this->maxWaitSeconds) {
                    throw $e;
                }

                $sleepMicros = max(0, (int) ($retryAfterSeconds * 1000000));
                usleep($sleepMicros);
            }
        }
    }

    private function extractRetryAfter(ResponseException $e): ?float
    {
        $message = $e->getMessage();
        $data = json_decode($message, true);

        if (is_array($data) && isset($data['retryAfter']) && is_numeric($data['retryAfter'])) {
            return (float) $data['retryAfter'];
        }

        // Check if message contains retry-after number
        if (preg_match('/retry-after:\s*(\d+)/i', $message, $matches)) {
            return (float) $matches[1];
        }

        return 1.0; // Default 1-second backoff on 429
    }
}
