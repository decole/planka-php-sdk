<?php

declare(strict_types=1);

namespace Planka\Bridge\Controllers;

use Planka\Bridge\Actions\Common\CommonPatchAction;
use Planka\Bridge\Actions\SystemConfig\SystemConfigGetAction;
use Planka\Bridge\Actions\SystemConfig\SystemConfigTestSmtpAction;
use Planka\Bridge\Actions\SystemConfig\SystemConfigUpdateAction;
use Planka\Bridge\Config;
use Planka\Bridge\Traits\SystemConfigHydrateTrait;
use Planka\Bridge\TransportClients\Client;
use Planka\Bridge\Views\Dto\SystemConfig\SystemConfigDto;

final class SystemConfig
{
    use SystemConfigHydrateTrait;

    public function __construct(
        private readonly Config $config,
        private readonly Client $client,
    ) {}

    /** 'GET /api/config' */
    public function get(): SystemConfigDto
    {
        return $this->client->get(new SystemConfigGetAction());
    }

    /** 'PATCH /api/config' */
    public function update(array $configData): SystemConfigDto
    {
        return $this->client->patch(new SystemConfigUpdateAction($configData));
    }

    /**
     * 'PATCH /api/config' - Partially updates system config properties.
     *
     * @param array{
     *   smtpHost?: string|null,
     *   smtpPort?: int|null,
     *   smtpName?: string|null,
     *   smtpSecure?: bool,
     *   smtpTlsRejectUnauthorized?: bool,
     *   smtpUser?: string|null,
     *   smtpPassword?: string|null,
     *   smtpFrom?: string|null
     * } $map Associative array of config fields to update
     */
    public function patching(array $map): SystemConfigDto
    {
        return $this->client->patch(new CommonPatchAction(
            urlPath: 'api/config',
            data: $map,
            hydrateCallback: fn($response) => $this->hydrate($response),
        ));
    }

    /** 'POST /api/config/test-smtp' */
    public function testSmtp(): array
    {
        return $this->client->post(new SystemConfigTestSmtpAction());
    }
}
