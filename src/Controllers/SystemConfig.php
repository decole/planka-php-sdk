<?php

declare(strict_types=1);

namespace Planka\Bridge\Controllers;

use Planka\Bridge\Actions\Common\CommonPatchAction;
use Planka\Bridge\Actions\SystemConfig\SystemConfigGetAction;
use Planka\Bridge\Actions\SystemConfig\SystemConfigTestSmtpAction;
use Planka\Bridge\Actions\SystemConfig\SystemConfigUpdateAction;
use Planka\Bridge\Contracts\Resources\SystemConfigResourceInterface;
use Planka\Bridge\TransportClients\TransportClientInterface;
use Planka\Bridge\Views\Dto\Common\TestResultDto;
use Planka\Bridge\Views\Dto\SystemConfig\SystemConfigDto;
use Planka\Bridge\Views\Factory\SystemConfig\SystemConfigDtoFactory;

final class SystemConfig implements SystemConfigResourceInterface
{
    public function __construct(private readonly TransportClientInterface $client) {}

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
            hydrateCallback: new SystemConfigDtoFactory(),
        ));
    }

    /** 'POST /api/config/test-smtp' */
    public function testSmtp(string $toEmail, ?array $smtpSettings = null): TestResultDto
    {
        return $this->client->post(new SystemConfigTestSmtpAction(toEmail: $toEmail, smtpSettings: $smtpSettings));
    }
}
