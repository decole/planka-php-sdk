<?php

declare(strict_types=1);

namespace Planka\Bridge\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Planka\Bridge\Actions\Project\ProjectListAction;
use Planka\Bridge\Config;
use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Pagination\Paginator;
use Planka\Bridge\PlankaClient;
use Planka\Bridge\PlankaClientInterface;
use Planka\Bridge\Security\SensitiveDataMasker;
use Planka\Bridge\TransportClients\Middleware\PsrLoggerMiddleware;
use Planka\Bridge\TransportClients\Middleware\TraceContextMiddleware;
use Psr\Log\AbstractLogger;

final class EnterpriseSecurityAndTracingTest extends TestCase
{
    public function testConfigSecretMaskingInDebugInfo(): void
    {
        $config = new Config(
            user: 'admin@example.com',
            password: 'super_secret_password',
            baseUri: 'http://localhost',
            port: 3000,
            apiKey: 'secret_api_key',
        );

        $debug = $config->__debugInfo();
        $this->assertEquals('********', $debug['password']);

        $tokenDebug = $config->getTokenStorage()->__debugInfo();
        $this->assertEquals('********', $tokenDebug['apiKey']);
    }

    public function testSensitiveDataMasker(): void
    {
        $payload = [
            'name' => 'Project Alpha',
            'password' => 'secret123',
            'currentPassword' => 'old_secret',
            'apiKey' => 'key_xyz',
            'nested' => [
                'token' => 'jwt_token',
                'description' => 'Public description',
            ],
        ];

        $masked = SensitiveDataMasker::maskArray($payload);

        $this->assertEquals('Project Alpha', $masked['name']);
        $this->assertEquals('********', $masked['password']);
        $this->assertEquals('********', $masked['currentPassword']);
        $this->assertEquals('********', $masked['apiKey']);
        $this->assertEquals('********', $masked['nested']['token']);
        $this->assertEquals('Public description', $masked['nested']['description']);

        $maskedUrl = SensitiveDataMasker::maskUrl('https://api.planka.com/users?apiKey=secret_key&limit=20');
        $this->assertStringContainsString('apiKey=%2A%2A%2A%2A%2A%2A%2A%2A', $maskedUrl);
        $this->assertStringContainsString('limit=20', $maskedUrl);
    }

    public function testTraceContextMiddleware(): void
    {
        $middleware = new TraceContextMiddleware(
            requestIdGenerator: 'req-custom-12345',
            traceparentGenerator: '00-4bf92f3577b34da6a3ce929d0e0e4736-00f067aa0ba902b7-01',
        );

        $action = new ProjectListAction();

        $capturedOptions = [];
        $next = function (ActionInterface $act, string $method) use (&$capturedOptions): mixed {
            $capturedOptions = $act->getOptions();

            return ['items' => []];
        };

        $middleware->handle($action, 'GET', $next);

        $this->assertArrayHasKey('headers', $capturedOptions);
        $this->assertEquals('req-custom-12345', $capturedOptions['headers']['X-Request-Id']);
        $this->assertEquals('00-4bf92f3577b34da6a3ce929d0e0e4736-00f067aa0ba902b7-01', $capturedOptions['headers']['traceparent']);
    }

    public function testPsrLoggerMiddlewareWithMasking(): void
    {
        $logs = [];
        $logger = new class ($logs) extends AbstractLogger {
            public function __construct(private array &$logs) {}

            public function log($level, $message, array $context = []): void
            {
                $this->logs[] = ['level' => $level, 'message' => $message, 'context' => $context];
            }
        };

        $middleware = new PsrLoggerMiddleware($logger, logOptions: true);
        $action = new class implements ActionInterface {
            public function url(): string
            {
                return 'api/users?apiKey=secret';
            }

            public function getOptions(): array
            {
                return ['json' => ['password' => 'super_secret']];
            }
        };

        $next = fn(ActionInterface $act, string $method) => ['status' => 'ok'];

        $middleware->handle($action, 'POST', $next);

        $this->assertCount(2, $logs);
        $reqLog = $logs[0];
        $this->assertStringContainsString('apiKey=%2A%2A%2A%2A%2A%2A%2A%2A', $reqLog['message']);
        $this->assertEquals('********', $reqLog['context']['options']['json']['password']);
    }

    public function testPaginator(): void
    {
        $pages = [
            null => ['items' => ['item1', 'item2'], 'nextCursor' => 'page2'],
            'page2' => ['items' => ['item3', 'item4'], 'nextCursor' => 'page3'],
            'page3' => ['items' => ['item5'], 'nextCursor' => null],
        ];

        $paginator = new Paginator(function (?string $cursor) use ($pages): array {
            return $pages[$cursor] ?? ['items' => [], 'nextCursor' => null];
        });

        $collected = $paginator->toArray();
        $this->assertEquals(['item1', 'item2', 'item3', 'item4', 'item5'], $collected);
        $this->assertCount(5, $paginator);
    }

    public function testPlankaClientInterfaceImplementation(): void
    {
        $config = new Config(baseUri: 'http://localhost', port: 3000);
        $client = new PlankaClient($config);

        $this->assertInstanceOf(PlankaClientInterface::class, $client);
    }

    public function testCircuitBreakerMiddleware(): void
    {
        $cb = new \Planka\Bridge\TransportClients\Middleware\CircuitBreakerMiddleware(
            failureThreshold: 2,
            recoveryTimeoutSeconds: 10,
        );

        $action = new ProjectListAction();
        $this->assertEquals('CLOSED', $cb->getState());

        $failingNext = function (): mixed {
            throw new \Planka\Bridge\Exceptions\PlankaServerException('Internal error', 500);
        };

        // Attempt 1 -> fails, remains CLOSED
        try {
            $cb->handle($action, 'GET', $failingNext);
        } catch (\Throwable) {
        }
        $this->assertEquals('CLOSED', $cb->getState());

        // Attempt 2 -> threshold reached, transitions to OPEN
        try {
            $cb->handle($action, 'GET', $failingNext);
        } catch (\Throwable) {
        }
        $this->assertEquals('OPEN', $cb->getState());

        // Subsequent call fails fast without calling next
        $this->expectException(\Planka\Bridge\Exceptions\PlankaServerUnavailableException::class);
        $cb->handle($action, 'GET', $failingNext);
    }

    public function testIdempotencyMiddleware(): void
    {
        $middleware = new \Planka\Bridge\TransportClients\Middleware\IdempotencyMiddleware();
        $action = new ProjectListAction();

        $capturedOptions = [];
        $next = function (ActionInterface $act) use (&$capturedOptions): mixed {
            $capturedOptions = $act->getOptions();

            return ['status' => 'ok'];
        };

        $middleware->handle($action, 'POST', $next);
        $this->assertArrayHasKey('headers', $capturedOptions);
        $this->assertArrayHasKey('Idempotency-Key', $capturedOptions['headers']);
        $this->assertNotEmpty($capturedOptions['headers']['Idempotency-Key']);
    }

    public function testMetricsMiddleware(): void
    {
        $collector = new \Planka\Bridge\Metrics\InMemoryMetricsCollector();
        $middleware = new \Planka\Bridge\TransportClients\Middleware\MetricsMiddleware($collector);

        $action = new ProjectListAction();
        $next = fn() => ['items' => []];

        $middleware->handle($action, 'GET', $next);

        $counters = $collector->getCounters();
        $this->assertArrayHasKey('planka_http_requests_total{method="GET",endpoint="api/projects",status="200"}', $counters);
        $this->assertEquals(1, $counters['planka_http_requests_total{method="GET",endpoint="api/projects",status="200"}']);

        $timings = $collector->getTimings();
        $this->assertArrayHasKey('planka_http_request_duration_ms{method="GET",endpoint="api/projects",status="200"}', $timings);
    }

    public function testBatchExecution(): void
    {
        $config = new Config(baseUri: 'http://localhost', port: 3000);
        $client = new PlankaClient($config);

        $actions = [
            'action1' => new ProjectListAction(),
            'action2' => new ProjectListAction(),
        ];

        $transportMock = $this->createMock(\Planka\Bridge\TransportClients\TransportClientInterface::class);
        $transportMock->expects($this->exactly(2))->method('get')->willReturn(['items' => []]);

        $batchExecutor = new \Planka\Bridge\TransportClients\BatchExecutor($transportMock);
        $results = $batchExecutor->execute($actions, 'GET');

        $this->assertArrayHasKey('action1', $results);
        $this->assertArrayHasKey('action2', $results);
    }
}
