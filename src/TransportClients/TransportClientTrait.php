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
        return EndpointUrlBuilder::build($config, $path);
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
        HttpResponseErrorHandler::handle($statusCode, $content);
    }
}
