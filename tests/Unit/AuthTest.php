<?php

declare(strict_types=1);

namespace Planka\Bridge\Tests\Unit;

final class AuthTest extends AbstractUnitTestCase
{
    public function testAuthenticateSuccess(): void
    {
        $client = $this->createMockClient('Auth/authenticate_success.json');
        $result = $client->authenticate();

        $this->assertTrue($result);
        $this->assertNotEmpty($this->config->getAuthToken());
    }

    public function testGetInfo(): void
    {
        $client = $this->createMockClient('System/get_info.json');
        $response = $client->getInfo();

        $this->assertEquals(200, $response->getStatusCode());
    }
}
