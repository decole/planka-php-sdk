<?php

declare(strict_types=1);

namespace Planka\Bridge\Metrics;

final class NullMetricsCollector implements MetricsCollectorInterface
{
    public function increment(string $name, array $labels = [], int $value = 1): void {}

    public function timing(string $name, float $durationMs, array $labels = []): void {}
}
