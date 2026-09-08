<?php

declare(strict_types=1);

namespace Planka\Bridge\Tests\Unit;

use Planka\Bridge\Exceptions\PlankaAccessDeniedException;
use Planka\Bridge\Exceptions\PlankaNotFoundException;
use Planka\Bridge\Exceptions\PlankaSdkExceptionInterface;
use Planka\Bridge\Exceptions\PlankaServerException;
use Planka\Bridge\Exceptions\PlankaValidationException;
use Planka\Bridge\Exceptions\ResponseException;

final class HttpErrorsTest extends AbstractUnitTestCase
{
    public function test404NotFoundException(): void
    {
        $client = $this->createMockClientWithResponse(
            body: json_encode(['message' => 'Board not found']),
            statusCode: 404,
        );

        $this->expectException(PlankaNotFoundException::class);
        $this->expectExceptionCode(404);

        try {
            $client->board()->get('non-existing-id');
        } catch (PlankaSdkExceptionInterface $e) {
            $this->assertEquals(404, $e->getStatusCode());

            throw $e;
        }
    }

    public function test400ValidationException(): void
    {
        $client = $this->createMockClientWithResponse(
            body: json_encode(['message' => 'Invalid parameters']),
            statusCode: 400,
        );

        $this->expectException(PlankaValidationException::class);
        $this->expectExceptionCode(400);

        try {
            $client->board()->get('invalid-id');
        } catch (PlankaSdkExceptionInterface $e) {
            $this->assertEquals(400, $e->getStatusCode());

            throw $e;
        }
    }

    public function test422ValidationException(): void
    {
        $client = $this->createMockClientWithResponse(
            body: json_encode(['message' => 'Unprocessable Entity']),
            statusCode: 422,
        );

        $this->expectException(PlankaValidationException::class);
        $this->expectExceptionCode(422);

        try {
            $client->board()->get('invalid-data');
        } catch (PlankaSdkExceptionInterface $e) {
            $this->assertEquals(422, $e->getStatusCode());

            throw $e;
        }
    }

    public function test401UnauthorizedException(): void
    {
        $client = $this->createMockClientWithResponse(
            body: json_encode(['message' => 'Unauthorized']),
            statusCode: 401,
        );

        $this->expectException(PlankaAccessDeniedException::class);
        $this->expectExceptionCode(401);

        try {
            $client->board()->get('any-id');
        } catch (PlankaSdkExceptionInterface $e) {
            $this->assertEquals(401, $e->getStatusCode());

            throw $e;
        }
    }

    public function test403ForbiddenException(): void
    {
        $client = $this->createMockClientWithResponse(
            body: json_encode(['message' => 'Forbidden']),
            statusCode: 403,
        );

        $this->expectException(PlankaAccessDeniedException::class);
        $this->expectExceptionCode(403);

        try {
            $client->board()->get('any-id');
        } catch (PlankaSdkExceptionInterface $e) {
            $this->assertEquals(403, $e->getStatusCode());

            throw $e;
        }
    }

    public function test500ServerException(): void
    {
        $client = $this->createMockClientWithResponse(
            body: json_encode(['message' => 'Internal Server Error']),
            statusCode: 500,
        );

        $this->expectException(PlankaServerException::class);
        $this->expectExceptionCode(500);

        try {
            $client->board()->get('any-id');
        } catch (PlankaSdkExceptionInterface $e) {
            $this->assertEquals(500, $e->getStatusCode());

            throw $e;
        }
    }

    public function testDefaultResponseException(): void
    {
        $client = $this->createMockClientWithResponse(
            body: json_encode(['message' => 'I am a teapot']),
            statusCode: 418,
        );

        $this->expectException(ResponseException::class);
        $this->expectExceptionCode(418);

        try {
            $client->board()->get('any-id');
        } catch (PlankaSdkExceptionInterface $e) {
            $this->assertEquals(418, $e->getStatusCode());

            throw $e;
        }
    }
}
