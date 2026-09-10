<?php

declare(strict_types=1);

namespace Planka\Bridge\TransportClients\Middleware;

use Planka\Bridge\Contracts\Actions\ActionInterface;

final class IdempotencyMiddleware implements TransportMiddlewareInterface
{
    /**
     * @param string                  $headerName   Header name for idempotency (default: Idempotency-Key)
     * @param callable(): string|null $keyGenerator Custom key generator callback
     */
    public function __construct(
        private readonly string $headerName = 'Idempotency-Key',
        private readonly mixed $keyGenerator = null,
    ) {}

    public function handle(ActionInterface $action, string $method, callable $next): mixed
    {
        if (!in_array(strtoupper($method), ['POST', 'PATCH'], true)) {
            return $next($action, $method);
        }

        if (is_callable($this->keyGenerator)) {
            $idempotencyKey = ($this->keyGenerator)();
        } else {
            $idempotencyKey = $this->generateUuidV4();
        }

        $actionWrapper = new class ($action, $this->headerName, $idempotencyKey) implements ActionInterface {
            public function __construct(
                private readonly ActionInterface $innerAction,
                private readonly string $header,
                private readonly string $key,
            ) {}

            public function url(): string
            {
                return $this->innerAction->url();
            }

            public function getOptions(): array
            {
                $options = $this->innerAction->getOptions();
                $headers = [];

                if (isset($options['headers']) && is_array($options['headers'])) {
                    $headers = $options['headers'];
                }

                if (!isset($headers[$this->header]) && !isset($headers[strtolower($this->header)])) {
                    $headers[$this->header] = $this->key;
                }

                $options['headers'] = $headers;

                return $options;
            }
        };

        return $next($actionWrapper, $method);
    }

    private function generateUuidV4(): string
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
