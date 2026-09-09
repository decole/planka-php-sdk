<?php

declare(strict_types=1);

namespace Planka\Bridge\TransportClients\Middleware;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

final class PsrLoggerMiddleware implements TransportMiddlewareInterface
{
    public function __construct(private readonly ?LoggerInterface $logger = null) {}

    public function handle(ActionInterface $action, string $method, callable $next): mixed
    {
        $logger = $this->logger ?? new NullLogger();

        $url = $action->url();
        $logger->info(sprintf('[Planka SDK] %s Request: %s', $method, $url));

        $start = microtime(true);

        try {
            $result = $next($action, $method);
            $duration = round((microtime(true) - $start) * 1000, 2);

            $logger->info(sprintf('[Planka SDK] %s Response for %s completed in %sms', $method, $url, $duration));

            return $result;
        } catch (\Throwable $e) {
            $duration = round((microtime(true) - $start) * 1000, 2);
            $logger->error(sprintf('[Planka SDK] %s Request failed for %s after %sms: %s', $method, $url, $duration, $e->getMessage()), [
                'exception' => $e,
            ]);

            throw $e;
        }
    }
}
