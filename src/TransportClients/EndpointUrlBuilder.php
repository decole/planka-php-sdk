<?php

declare(strict_types=1);

namespace Planka\Bridge\TransportClients;

use Planka\Bridge\Config;

final class EndpointUrlBuilder
{
    public static function build(Config $config, string $path): string
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
}
