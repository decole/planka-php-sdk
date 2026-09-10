<?php

declare(strict_types=1);

namespace Planka\Bridge\TransportClients;

use Planka\Bridge\Exceptions\PlankaAccessDeniedException;
use Planka\Bridge\Exceptions\PlankaNotFoundException;
use Planka\Bridge\Exceptions\PlankaServerException;
use Planka\Bridge\Exceptions\PlankaValidationException;
use Planka\Bridge\Exceptions\ResponseException;

final class HttpResponseErrorHandler
{
    /**
     * @throws PlankaNotFoundException
     * @throws PlankaValidationException
     * @throws PlankaAccessDeniedException
     * @throws PlankaServerException
     * @throws ResponseException
     */
    public static function handle(int $statusCode, string $content): void
    {
        if ($statusCode >= 200 && $statusCode < 300) {
            return;
        }

        match (true) {
            404 === $statusCode => throw new PlankaNotFoundException($content, 404),
            400 === $statusCode, 422 === $statusCode => throw new PlankaValidationException($content, $statusCode),
            401 === $statusCode, 403 === $statusCode => throw new PlankaAccessDeniedException($content, $statusCode),
            $statusCode >= 500 => throw new PlankaServerException($content, $statusCode),
            default => throw new ResponseException($content, $statusCode),
        };
    }
}
