<?php

declare(strict_types=1);

namespace Planka\Bridge\TransportClients\Middleware;

use Planka\Bridge\Contracts\Actions\ActionInterface;

final class TraceContextMiddleware implements TransportMiddlewareInterface
{
    /**
     * @param string|callable(): string|null $requestIdGenerator   Generator for X-Request-Id
     * @param string|callable(): string|null $traceparentGenerator Generator for W3C traceparent header
     * @param string                         $requestIdHeader      Header name for request ID (default: X-Request-Id)
     */
    public function __construct(
        private readonly mixed $requestIdGenerator = null,
        private readonly mixed $traceparentGenerator = null,
        private readonly string $requestIdHeader = 'X-Request-Id',
    ) {}

    public function handle(ActionInterface $action, string $method, callable $next): mixed
    {
        $requestId = $this->resolveValue($this->requestIdGenerator) ?? $this->generateDefaultRequestId();
        $traceparent = $this->resolveValue($this->traceparentGenerator);

        $actionWrapper = new class ($action, $this->requestIdHeader, $requestId, $traceparent) implements ActionInterface {
            public function __construct(
                private readonly ActionInterface $innerAction,
                private readonly string $headerName,
                private readonly string $reqId,
                private readonly ?string $traceparent,
            ) {}

            public function url(): string
            {
                return $this->innerAction->url();
            }

            public function getOptions(): array
            {
                $options = $this->innerAction->getOptions();
                $headers = is_array($options['headers'] ?? null) ? $options['headers'] : [];

                $headers[$this->headerName] = $this->reqId;

                if (null !== $this->traceparent) {
                    $headers['traceparent'] = $this->traceparent;
                }

                $options['headers'] = $headers;

                return $options;
            }
        };

        return $next($actionWrapper, $method);
    }

    private function resolveValue(mixed $generator): ?string
    {
        if (is_string($generator)) {
            return $generator;
        }

        if (is_callable($generator)) {
            $val = $generator();

            return is_string($val) ? $val : null;
        }

        return null;
    }

    private function generateDefaultRequestId(): string
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            random_int(0, 0xFFFF),
            random_int(0, 0xFFFF),
            random_int(0, 0xFFFF),
            random_int(0, 0x0FFF) | 0x4000,
            random_int(0, 0x3FFF) | 0x8000,
            random_int(0, 0xFFFF),
            random_int(0, 0xFFFF),
            random_int(0, 0xFFFF),
        );
    }
}
