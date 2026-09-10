<?php

declare(strict_types=1);

namespace Planka\Bridge\Contracts\Resources;

use Planka\Bridge\Views\Dto\Common\TestResultDto;
use Planka\Bridge\Views\Dto\SystemConfig\SystemConfigDto;

interface SystemConfigResourceInterface
{
    public function get(): SystemConfigDto;

    /**
     * @param array<string, mixed> $configData
     */
    public function update(array $configData): SystemConfigDto;

    /**
     * @param array{
     *   smtpHost?: string|null,
     *   smtpPort?: int|null,
     *   smtpName?: string|null,
     *   smtpSecure?: bool,
     *   smtpTlsRejectUnauthorized?: bool,
     *   smtpUser?: string|null,
     *   smtpPassword?: string|null,
     *   smtpFrom?: string|null
     * } $map
     */
    public function patching(array $map): SystemConfigDto;

    /**
     * @param array<string, mixed>|null $smtpSettings
     */
    public function testSmtp(string $toEmail, ?array $smtpSettings = null): TestResultDto;
}
