<?php

declare(strict_types=1);

namespace Planka\Bridge\TransportClients\Middleware;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Exceptions\PlankaServerException;
use Planka\Bridge\Exceptions\PlankaServerUnavailableException;

final class CircuitBreakerMiddleware implements TransportMiddlewareInterface
{
    public const STATE_CLOSED = 'CLOSED';
    public const STATE_OPEN = 'OPEN';
    public const STATE_HALF_OPEN = 'HALF_OPEN';

    private string $state = self::STATE_CLOSED;

    private int $failureCount = 0;

    private ?float $openedAt = null;

    /**
     * @param int $failureThreshold       Consecutive failures before opening circuit (default: 5)
     * @param int $recoveryTimeoutSeconds Seconds to wait before attempting recovery (default: 30s)
     */
    public function __construct(
        private readonly int $failureThreshold = 5,
        private readonly int $recoveryTimeoutSeconds = 30,
    ) {}

    public function handle(ActionInterface $action, string $method, callable $next): mixed
    {
        $this->checkState();

        try {
            $result = $next($action, $method);
            $this->onSuccess();

            return $result;
        } catch (\Throwable $e) {
            $this->onFailure($e);

            throw $e;
        }
    }

    public function getState(): string
    {
        if (self::STATE_OPEN === $this->state && null !== $this->openedAt) {
            if ((microtime(true) - $this->openedAt) >= $this->recoveryTimeoutSeconds) {
                $this->state = self::STATE_HALF_OPEN;
            }
        }

        return $this->state;
    }

    private function checkState(): void
    {
        if (self::STATE_OPEN === $this->state && null !== $this->openedAt) {
            if ((microtime(true) - $this->openedAt) >= $this->recoveryTimeoutSeconds) {
                $this->state = self::STATE_HALF_OPEN;

                return;
            }

            throw new PlankaServerUnavailableException(sprintf('Circuit breaker is OPEN. Planka API server is temporarily unavailable (opened at %s).', (int) $this->openedAt));
        }
    }

    private function onSuccess(): void
    {
        $this->failureCount = 0;
        $this->state = self::STATE_CLOSED;
        $this->openedAt = null;
    }

    private function onFailure(\Throwable $e): void
    {
        if ($this->isCircuitFailure($e)) {
            ++$this->failureCount;

            if ($this->failureCount >= $this->failureThreshold || self::STATE_HALF_OPEN === $this->state) {
                $this->state = self::STATE_OPEN;
                $this->openedAt = microtime(true);
            }
        }
    }

    private function isCircuitFailure(\Throwable $e): bool
    {
        if ($e instanceof PlankaServerException || $e instanceof PlankaServerUnavailableException) {
            return true;
        }

        $code = $e->getCode();

        return in_array($code, [500, 502, 503, 504], true);
    }
}
