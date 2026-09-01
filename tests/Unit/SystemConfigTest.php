<?php

declare(strict_types=1);

namespace Planka\Bridge\Tests\Unit;

use Planka\Bridge\Views\Dto\SystemConfig\SystemConfigDto;

final class SystemConfigTest extends AbstractUnitTestCase
{
    public function testGetSystemConfig(): void
    {
        $client = $this->createMockClient('System/system_config.json');
        $config = $client->systemConfig->get();

        $this->assertInstanceOf(SystemConfigDto::class, $config);
        $this->assertNotEmpty($config->id);
    }
}
