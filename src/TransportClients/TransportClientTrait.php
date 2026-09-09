<?php

declare(strict_types=1);

namespace Planka\Bridge\TransportClients;

use Planka\Bridge\Config;
use Planka\Bridge\Exceptions\PlankaAccessDeniedException;
use Planka\Bridge\Exceptions\PlankaNotFoundException;
use Planka\Bridge\Exceptions\PlankaServerException;
use Planka\Bridge\Exceptions\PlankaValidationException;
use Planka\Bridge\Exceptions\ResponseException;

trait TransportClientTrait
{
    private function buildUrl(Config $config, string $path): string
    {
        $base = rtrim($config->getBaseUri(), '/');

        if (
            80 !== $config->getPort()
            && 443 !== $config->getPort()
            && false === strpos($base, ':', 7)
        ) {
            $base .= ':' . $config->getPort();
        }

        return $base . '/' . ltrim($path, '/');
    }

    /**
     * @throws PlankaNotFoundException
     * @throws PlankaValidationException
     * @throws PlankaAccessDeniedException
     * @throws PlankaServerException
     * @throws ResponseException
     */
    private function handleResponseStatus(int $statusCode, string $content): void
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
