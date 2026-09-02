<?php

declare(strict_types=1);

namespace Planka\Bridge\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Planka\Bridge\Views\Dto\User\UserDto;
use Planka\Bridge\Views\Factory\User\UserDtoFactory;

final class UserDtoTest extends TestCase
{
    public function testCreateUserFromPlankaV2Payload(): void
    {
        $payload = [
            'id' => '1853641278592386049',
            'createdAt' => '2026-08-31T07:54:19.894Z',
            'updatedAt' => '2026-09-01T19:14:25.680Z',
            'email' => 'decole@rambler.ru',
            'role' => 'admin',
            'name' => 'decole',
            'username' => 'decole',
            'phone' => null,
            'organization' => 'HIKSDB13',
            'apiKeyPrefix' => null,
            'isDeactivated' => false,
            'isTotpEnabled' => false,
            'totpEnabledAt' => null,
            'avatar' => null,
            'totpRecoveryCodesRemaining' => 0,
            'isDefaultAdmin' => false,
            'lockedFieldNames' => [],
        ];

        $user = (new UserDtoFactory())->create($payload);

        $this->assertInstanceOf(UserDto::class, $user);
        $this->assertEquals('1853641278592386049', $user->id);
        $this->assertEquals('decole@rambler.ru', $user->email);
        $this->assertEquals('decole', $user->username);
        $this->assertTrue($user->isAdmin);
        $this->assertEquals('HIKSDB13', $user->organization);
    }

    public function testCreateNonAdminUserFromPlankaV2Payload(): void
    {
        $payload = [
            'id' => '1853641278592386050',
            'createdAt' => '2026-08-31T07:54:19.894Z',
            'updatedAt' => null,
            'email' => 'user@example.com',
            'role' => 'boardUser',
            'name' => 'Regular User',
            'username' => 'regularuser',
        ];

        $user = (new UserDtoFactory())->create($payload);

        $this->assertInstanceOf(UserDto::class, $user);
        $this->assertEquals('1853641278592386050', $user->id);
        $this->assertFalse($user->isAdmin);
    }
}
