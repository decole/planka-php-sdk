<?php

declare(strict_types=1);

namespace Planka\Bridge\Metrics;

interface MetricsCollectorInterface
{
    /**
     * Increment a counter metric.
     *
     * @param string                $name   Metric name
     * @param array<string, string> $labels Key-value labels (e.g. ['method' => 'GET', 'endpoint' => 'api/boards', 'status' => '200'])
     */
    public function increment(string $name, array $labels = [], int $value = 1): void;

    /**
     * Record a duration/latency timing metric in milliseconds.
     *
     * @param string                $name       Metric name
     * @param float                 $durationMs Duration in milliseconds
     * @param array<string, string> $labels     Key-value labels
     */
    public function timing(string $name, float $durationMs, array $labels = []): void;
}
