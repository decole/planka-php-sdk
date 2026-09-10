<?php

declare(strict_types=1);

namespace Planka\Bridge\TransportClients\Middleware;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Metrics\MetricsCollectorInterface;
use Planka\Bridge\Metrics\NullMetricsCollector;

final class MetricsMiddleware implements TransportMiddlewareInterface
{
    public function __construct(
        private readonly ?MetricsCollectorInterface $collector = null,
        private readonly string $metricPrefix = 'planka_http',
    ) {}

    public function handle(ActionInterface $action, string $method, callable $next): mixed
    {
        $collector = $this->collector ?? new NullMetricsCollector();
        $endpoint = $this->normalizeEndpoint($action->url());
        $startTime = microtime(true);

        try {
            $result = $next($action, $method);
            $durationMs = (microtime(true) - $startTime) * 1000;

            $labels = [
                'method' => $method,
                'endpoint' => $endpoint,
                'status' => '200',
            ];

            $collector->increment("{$this->metricPrefix}_requests_total", $labels);
            $collector->timing("{$this->metricPrefix}_request_duration_ms", $durationMs, $labels);

            return $result;
        } catch (\Throwable $e) {
            $durationMs = (microtime(true) - $startTime) * 1000;
            $statusCode = (string) $e->getCode();

            $labels = [
                'method' => $method,
                'endpoint' => $endpoint,
                'status' => '' !== $statusCode && '0' !== $statusCode ? $statusCode : '500',
                'exception' => (new \ReflectionClass($e))->getShortName(),
            ];

            $collector->increment("{$this->metricPrefix}_requests_total", $labels);
            $collector->increment("{$this->metricPrefix}_errors_total", $labels);
            $collector->timing("{$this->metricPrefix}_request_duration_ms", $durationMs, $labels);

            throw $e;
        }
    }

    private function normalizeEndpoint(string $url): string
    {
        // Strip query string
        $path = explode('?', $url, 2)[0];

        // Replace IDs (numeric or 16+ character IDs) with placeholders for low metric cardinality
        return preg_replace('#/[0-9a-fA-F-]{16,}#', '/:id', $path);
    }
}
