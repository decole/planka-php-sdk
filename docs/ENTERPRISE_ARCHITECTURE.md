# Enterprise Architecture & Production Readiness

Planka PHP SDK v2 is engineered as a high-reliability, enterprise-grade client library designed for high-load production systems, microservices, asynchronous message consumers (Symfony Messenger, Laravel Queue), and long-running workers (RoadRunner, Swoole, FrankenPHP).

---

## 1. Security & Sensitive Data Masking

### A. Automatic Debug Masking (`__debugInfo()`)
To prevent accidental leaks of sensitive credentials in error trackers (e.g., Sentry, Bugsnag, Datadog), stack traces, or terminal `var_dump()` output, `Config` and `InMemoryTokenStorage` automatically mask secrets:

```php
$config = new Config(
    user: 'admin@example.com',
    password: 'super_secret_password',
    baseUri: 'http://192.168.1.100',
    port: 3000,
    apiKey: 'secret_api_key_123'
);

var_dump($config);
// Output:
// 'user' => 'admin@example.com'
// 'password' => '********'
// 'apiKey' => '********'
```

### B. PSR-3 Logging Masker (`SensitiveDataMasker`)
`PsrLoggerMiddleware` uses `SensitiveDataMasker` to sanitize request URLs and options before logging, preventing authentication tokens, passwords, and API keys from leaking into log aggregators (ELK, CloudWatch, Loki):

```php
use Planka\Bridge\Config;
use Planka\Bridge\PlankaClient;
use Planka\Bridge\TransportClients\Middleware\PsrLoggerMiddleware;

$planka = new PlankaClient(
    config: $config,
    middlewares: [
        new PsrLoggerMiddleware($logger, logOptions: true), // Options and URLs are sanitized automatically
    ]
);
```

### C. Webhook HMAC Verification with Replay Attack Protection
`WebhookParser::verifySignature` validates cryptographic authenticity and ensures requests are within a configurable time window to defeat replay attacks:

```php
use Planka\Bridge\Webhook\WebhookParser;

$parser = new WebhookParser();

$rawBody = file_get_contents('php://input');
$signature = $_SERVER['HTTP_X_PLANKA_SIGNATURE'] ?? '';
$timestamp = isset($_SERVER['HTTP_X_PLANKA_TIMESTAMP']) ? (int) $_SERVER['HTTP_X_PLANKA_TIMESTAMP'] : null;

// Rejects requests if HMAC is invalid OR timestamp is older than 300 seconds
if (!$parser->verifySignature(
    payload: $rawBody,
    secret: 'your_webhook_secret',
    signatureHeader: $signature,
    timestamp: $timestamp,
    toleranceSeconds: 300
)) {
    http_response_code(403);
    exit('Unauthorized / Replay detected');
}
```

---

## 2. Long-Running Workers, Resilience & Traffic Control

### A. Automatic JWT Token Refresh (`TokenRefreshMiddleware`)
In background daemon processes (CLI consumers, queue workers), JWT authentication tokens eventually expire. `TokenRefreshMiddleware` transparently intercepts `401 Unauthorized` responses, re-authenticates using the credentials in `Config`, updates `TokenStorage`, and retries the original request:

```php
use Planka\Bridge\Config;
use Planka\Bridge\PlankaClient;
use Planka\Bridge\TransportClients\Middleware\TokenRefreshMiddleware;
use Planka\Bridge\TransportClients\Middleware\RetryMiddleware;

$config = new Config(
    user: 'bot@example.com',
    password: 'secure_bot_password',
    baseUri: 'http://192.168.1.100',
    port: 3000
);

$planka = new PlankaClient(
    config: $config,
    middlewares: [
        new TokenRefreshMiddleware($config),
        new RetryMiddleware(maxRetries: 3, baseDelayMs: 200),
    ]
);
```

### B. Circuit Breaker (`CircuitBreakerMiddleware`)
Protects your application from cascading failures when the Planka API server is down or timing out. Transitions through `CLOSED` $\to$ `OPEN` $\to$ `HALF_OPEN` states, failing fast without tying up worker threads:

```php
use Planka\Bridge\TransportClients\Middleware\CircuitBreakerMiddleware;

$circuitBreaker = new CircuitBreakerMiddleware(
    failureThreshold: 5,        // Open circuit after 5 consecutive 5xx/timeout errors
    recoveryTimeoutSeconds: 30  // Wait 30s before trying recovery probe
);

$planka = new PlankaClient($config, middlewares: [$circuitBreaker]);
```

### C. Rate Limiting & `Retry-After` Handling (`RateLimiterMiddleware`)
Automatically handles `429 Too Many Requests` responses by reading `Retry-After` headers and backing off before retrying:

```php
use Planka\Bridge\TransportClients\Middleware\RateLimiterMiddleware;

$rateLimiter = new RateLimiterMiddleware(maxWaitSeconds: 60);

$planka = new PlankaClient($config, middlewares: [$rateLimiter]);
```

### D. Idempotency Key Support (`IdempotencyMiddleware`)
Generates and attaches `Idempotency-Key` UUIDv4 headers to `POST` and `PATCH` requests to prevent duplicate resource creations during network retries:

```php
use Planka\Bridge\TransportClients\Middleware\IdempotencyMiddleware;

$idempotency = new IdempotencyMiddleware();

$planka = new PlankaClient($config, middlewares: [$idempotency]);
```

### E. Metrics & Observability (`MetricsMiddleware`)
Collects request counts, latencies, and error rates with normalized low-cardinality endpoint labels for Prometheus / StatsD / OpenTelemetry:

```php
use Planka\Bridge\Metrics\InMemoryMetricsCollector;
use Planka\Bridge\TransportClients\Middleware\MetricsMiddleware;

$metricsCollector = new InMemoryMetricsCollector(); // Or your custom Prometheus adapter
$metricsMiddleware = new MetricsMiddleware($metricsCollector);

$planka = new PlankaClient($config, middlewares: [$metricsMiddleware]);
```

### F. Distributed Tracing (`TraceContextMiddleware`)
Propagates correlation IDs (`X-Request-Id`) and OpenTelemetry / W3C Trace Context headers (`traceparent`) downstream to Planka for end-to-end distributed tracing:

```php
use Planka\Bridge\Config;
use Planka\Bridge\PlankaClient;
use Planka\Bridge\TransportClients\Middleware\TraceContextMiddleware;

$planka = new PlankaClient(
    config: $config,
    middlewares: [
        new TraceContextMiddleware(
            requestIdGenerator: fn() => $currentRequestContext->getRequestId(),
            traceparentGenerator: fn() => $currentRequestContext->getTraceparent(),
        ),
    ]
);
```

---

## 3. Inversion of Control (IoC) & 100% Interface Isolation

All 23 API resources implement dedicated interfaces under `Planka\Bridge\Contracts\Resources\*`, and the client implements `PlankaClientInterface`. This allows seamless mocking in unit tests and clean dependency injection:

```php
use Planka\Bridge\Contracts\Resources\CardResourceInterface;
use Planka\Bridge\Contracts\Resources\ProjectResourceInterface;
use Planka\Bridge\PlankaClientInterface;

class TaskManagementService
{
    public function __construct(
        private readonly PlankaClientInterface $planka,
    ) {}

    public function createTicket(string $listId, string $title): void
    {
        $this->planka->card()->create(listId: $listId, nameOrInput: $title);
    }
}
```

### Unit Testing Example with PHPUnit:

```php
use PHPUnit\Framework\TestCase;
use Planka\Bridge\Contracts\Resources\CardResourceInterface;
use Planka\Bridge\PlankaClientInterface;
use Planka\Bridge\Views\Dto\Card\CardDto;

class TaskManagementServiceTest extends TestCase
{
    public function testCreateTicket(): void
    {
        $mockCardResource = $this->createMock(CardResourceInterface::class);
        $mockCardResource->expects($this->once())
            ->method('create')
            ->with('list_123', 'Implement SSO');

        $mockClient = $this->createMock(PlankaClientInterface::class);
        $mockClient->method('card')->willReturn($mockCardResource);

        $service = new TaskManagementService($mockClient);
        $service->createTicket('list_123', 'Implement SSO');
    }
}
```

---

## 4. Batch Operations & Lazy Pagination

### A. Batch Request Execution (`$planka->batch(...)`)
Executes an array of actions in a single call:

```php
use Planka\Bridge\Actions\Project\ProjectViewAction;

$results = $planka->batch([
    'project1' => new ProjectViewAction('proj_123'),
    'project2' => new ProjectViewAction('proj_456'),
]);

$proj1 = $results['project1']; // Hydrated ProjectDto
```

### B. Memory-Efficient Lazy Pagination (`Paginator`)
For iterating over large datasets without loading entire collections into memory, `Paginator` uses PHP Generators (`yield`):

```php
use Planka\Bridge\Pagination\Paginator;

$paginator = new Paginator(function (?string $beforeId) use ($planka, $boardId) {
    $actionList = $planka->board()->getActions($boardId, $beforeId);
    $items = $actionList->items;
    $lastItem = end($items);

    return [
        'items' => $items,
        'nextCursor' => $lastItem ? $lastItem->id : null,
    ];
});

// Stream through items one by one with constant memory usage
foreach ($paginator as $action) {
    echo "Action {$action->id}: {$action->type->value}\n";
}
```

---

## 5. Framework Integration Recipes

### Symfony Integration (`services.yaml`):

```yaml
services:
    Planka\Bridge\Config:
        arguments:
            $baseUri: '%env(PLANKA_URL)%'
            $apiKey: '%env(PLANKA_API_KEY)%'

    Planka\Bridge\TransportClients\Middleware\PsrLoggerMiddleware:
        arguments:
            $logger: '@logger'

    Planka\Bridge\PlankaClient:
        arguments:
            $config: '@Planka\Bridge\Config'
            $middlewares:
                - '@Planka\Bridge\TransportClients\Middleware\PsrLoggerMiddleware'

    Planka\Bridge\PlankaClientInterface: '@Planka\Bridge\PlankaClient'
```

### Laravel Integration (`AppServiceProvider.php`):

```php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Planka\Bridge\Config;
use Planka\Bridge\PlankaClient;
use Planka\Bridge\PlankaClientInterface;
use Planka\Bridge\TransportClients\Middleware\TokenRefreshMiddleware;

class PlankaServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(PlankaClientInterface::class, function ($app) {
            $config = new Config(
                baseUri: config('services.planka.url'),
                apiKey: config('services.planka.api_key'),
            );

            return new PlankaClient(
                config: $config,
                middlewares: [
                    new TokenRefreshMiddleware($config),
                ]
            );
        });
    }
}
```
