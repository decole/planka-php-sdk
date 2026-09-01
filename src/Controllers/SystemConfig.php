<?php

declare(strict_types=1);

namespace Planka\Bridge\Controllers;

use Planka\Bridge\Actions\SystemConfig\SystemConfigGetAction;
use Planka\Bridge\Actions\SystemConfig\SystemConfigTestSmtpAction;
use Planka\Bridge\Actions\SystemConfig\SystemConfigUpdateAction;
use Planka\Bridge\Config;
use Planka\Bridge\TransportClients\Client;
use Planka\Bridge\Views\Dto\SystemConfig\SystemConfigDto;

final class SystemConfig
{
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

    /** 'POST /api/config/test-smtp' */
    public function testSmtp(): array
    {
        return $this->client->post(new SystemConfigTestSmtpAction());
    }
}
