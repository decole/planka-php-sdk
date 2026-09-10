<?php

declare(strict_types=1);

namespace Planka\Bridge\Tests\Unit;

use Planka\Bridge\Views\Dto\Auth\AuthenticateResultDto;
use Planka\Bridge\Views\Dto\User\TotpSetupDto;
use Planka\Bridge\Views\Dto\User\UserDto;

final class AuthTotpTest extends AbstractUnitTestCase
{
    public function testAuthenticateSuccess(): void
    {
        $mockJson = json_encode(['item' => 'valid_jwt_token_string']);
        $client = $this->createMockClientWithResponse($mockJson);

        $result = $client->authenticate();

        $this->assertInstanceOf(AuthenticateResultDto::class, $result);
        $this->assertTrue($result->success);
        $this->assertEquals('valid_jwt_token_string', $result->token);
    }

    public function testAuthenticateTotpRequired(): void
    {
        $errorJson = json_encode([
            'message' => 'TOTP verification required',
            'pendingToken' => 'pending_totp_token_123',
        ]);

        $client = $this->createMockClientWithResponse(
            body: $errorJson,
            statusCode: 403,
        );

        $result = $client->authenticate();

        $this->assertInstanceOf(AuthenticateResultDto::class, $result);
        $this->assertFalse($result->success);
        $this->assertTrue($result->requiresTotp());
        $this->assertFalse($result->requiresTerms());
        $this->assertEquals('pending_totp_token_123', $result->pendingToken);
    }

    public function testAuthenticateTermsRequired(): void
    {
        $errorJson = json_encode([
            'message' => 'Terms acceptance required',
            'item' => 'pending_terms_token_456',
        ]);

        $client = $this->createMockClientWithResponse(
            body: $errorJson,
            statusCode: 403,
        );

        $result = $client->authenticate();

        $this->assertInstanceOf(AuthenticateResultDto::class, $result);
        $this->assertFalse($result->success);
        $this->assertTrue($result->requiresTerms());
        $this->assertFalse($result->requiresTotp());
        $this->assertEquals('pending_terms_token_456', $result->pendingToken);
    }

    public function testVerifyTotp(): void
    {
        $mockJson = json_encode(['item' => 'verified_jwt_token']);
        $client = $this->createMockClientWithResponse($mockJson);

        $result = $client->verifyTotp('pending_totp_token_123', '123456');

        $this->assertInstanceOf(AuthenticateResultDto::class, $result);
        $this->assertTrue($result->success);
        $this->assertEquals('verified_jwt_token', $result->token);
    }

    public function testUserTotpSetupAndEnable(): void
    {
        $setupJson = json_encode([
            'item' => [
                'secret' => 'JBSWY3DPEHPK3PXP',
                'provisioningUri' => 'otpauth://totp/Planka:user@example.com?secret=JBSWY3DPEHPK3PXP',
            ],
        ]);

        $client = $this->createMockClientWithResponse($setupJson);
        $setup = $client->user()->setupTotp('user123', 'password123');

        $this->assertInstanceOf(TotpSetupDto::class, $setup);
        $this->assertEquals('JBSWY3DPEHPK3PXP', $setup->secret);

        $userJson = json_encode([
            'item' => [
                'id' => 'user123',
                'createdAt' => '2026-09-08T00:00:00.000Z',
                'updatedAt' => '2026-09-08T00:00:00.000Z',
                'email' => 'user@example.com',
                'name' => 'User Test',
            ],
        ]);

        $client2 = $this->createMockClientWithResponse($userJson);
        $user = $client2->user()->enableTotp('user123', 'password123', '123456');

        $this->assertInstanceOf(UserDto::class, $user);
        $this->assertEquals('user123', $user->id);
    }
}
