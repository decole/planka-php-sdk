<?php

declare(strict_types=1);

namespace Planka\Bridge\Metrics;

final class InMemoryMetricsCollector implements MetricsCollectorInterface
{
    /** @var array<string, int> */
    private array $counters = [];

    /** @var array<string, list<float>> */
    private array $timings = [];

    public function increment(string $name, array $labels = [], int $value = 1): void
    {
        $key = $this->buildKey($name, $labels);
        $this->counters[$key] = ($this->counters[$key] ?? 0) + $value;
    }

    public function timing(string $name, float $durationMs, array $labels = []): void
    {
        $key = $this->buildKey($name, $labels);
        $this->timings[$key][] = $durationMs;
    }

    /**
     * @return array<string, int>
     */
    public function getCounters(): array
    {
        return $this->counters;
    }

    /**
     * @return array<string, list<float>>
     */
    public function getTimings(): array
    {
        return $this->timings;
    }

    public function reset(): void
    {
        $this->counters = [];
        $this->timings = [];
    }

    /**
     * @param array<string, string> $labels
     */
    private function buildKey(string $name, array $labels): string
    {
        if (empty($labels)) {
            return $name;
        }

        $formatted = [];

        foreach ($labels as $k => $v) {
            $formatted[] = "{$k}=\"{$v}\"";
        }

        return $name . '{' . implode(',', $formatted) . '}';
    }
}
