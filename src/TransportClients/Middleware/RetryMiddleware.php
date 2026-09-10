<?php

declare(strict_types=1);

namespace Planka\Bridge\TransportClients\Middleware;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Exceptions\PlankaServerException;

final class RetryMiddleware implements TransportMiddlewareInterface
{
    /**
     * @param int $maxRetries  Maximum retry attempts (default 3)
     * @param int $baseDelayMs Base delay in milliseconds (default 200ms)
     */
    public function __construct(
        private readonly int $maxRetries = 3,
        private readonly int $baseDelayMs = 200,
    ) {}

    public function handle(ActionInterface $action, string $method, callable $next): mixed
    {
        $attempts = 0;

        while (true) {
            try {
                return $next($action, $method);
            } catch (\Throwable $e) {
                if (++$attempts > $this->maxRetries || !$this->isRetryable($e)) {
                    throw $e;
                }

                $delayMs = max(0, (int) ($this->baseDelayMs * (2 ** ($attempts - 1))));

                usleep($delayMs * 1000);
            }
        }
    }

    private function isRetryable(\Throwable $e): bool
    {
        if ($e instanceof PlankaServerException) {
            return true;
        }

        $code = $e->getCode();

        return in_array($code, [429, 502, 503, 504], true);
    }
}
