<?php

declare(strict_types=1);

namespace Planka\Bridge\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Planka\Bridge\Actions\Auth\AuthenticateAction;
use Planka\Bridge\Actions\Project\ProjectListAction;
use Planka\Bridge\Config;
use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Exceptions\PlankaAccessDeniedException;
use Planka\Bridge\TransportClients\Middleware\TokenRefreshMiddleware;

final class TokenRefreshMiddlewareTest extends TestCase
{
    public function testTokenRefreshOn401(): void
    {
        $config = new Config(
            user: 'admin@example.com',
            password: 'secret_password',
            baseUri: 'http://localhost',
            port: 3000,
        );
        $config->setAuthToken('expired_token');

        $middleware = new TokenRefreshMiddleware($config);
        $action = new ProjectListAction();

        $callCount = 0;
        $next = function (ActionInterface $act, string $method) use (&$callCount): mixed {
            ++$callCount;

            if ($act instanceof AuthenticateAction) {
                return ['item' => 'fresh_jwt_token_123'];
            }

            if (1 === $callCount) {
                // First call fails with 401
                throw new PlankaAccessDeniedException('Token expired', 401);
            }

            // Second call succeeds with fresh token
            return ['items' => []];
        };

        $result = $middleware->handle($action, 'GET', $next);

        $this->assertEquals(['items' => []], $result);
        $this->assertEquals('fresh_jwt_token_123', $config->getAuthToken());
        $this->assertEquals(3, $callCount); // Initial request (401), Auth request, Retried initial request
    }

    public function testNon401ExceptionIsNotRefreshed(): void
    {
        $config = new Config(
            user: 'admin@example.com',
            password: 'secret_password',
            baseUri: 'http://localhost',
            port: 3000,
        );

        $middleware = new TokenRefreshMiddleware($config);
        $action = new ProjectListAction();

        $next = function (): mixed {
            throw new PlankaAccessDeniedException('Forbidden', 403);
        };

        $this->expectException(PlankaAccessDeniedException::class);
        $this->expectExceptionCode(403);

        $middleware->handle($action, 'GET', $next);
    }
}
