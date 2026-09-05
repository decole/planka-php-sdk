<?php

declare(strict_types=1);

namespace Planka\Bridge\Tests\Unit;

use Planka\Bridge\Enum\LanguageEnum;
use Planka\Bridge\Views\Dto\AccessToken\AccessTokenDto;
use Planka\Bridge\Views\Dto\Terms\TermsDto;

final class AuthEndpointsTest extends AbstractUnitTestCase
{
    public function testGetTerms(): void
    {
        $mockJson = json_encode([
            'item' => [
                'language' => 'en-US',
                'content' => 'Terms content markdown',
                'signature' => 'signature_hash_123',
            ],
        ]);

        $client = $this->createMockClientWithResponse($mockJson);
        $terms = $client->terms->get(LanguageEnum::EN_US);

        $this->assertInstanceOf(TermsDto::class, $terms);
        $this->assertEquals('en-US', $terms->language);
        $this->assertEquals('Terms content markdown', $terms->content);
        $this->assertEquals('signature_hash_123', $terms->signature);
    }

    public function testAcceptTerms(): void
    {
        $mockJson = json_encode([
            'item' => 'access_token_jwt_token',
        ]);

        $client = $this->createMockClientWithResponse($mockJson);
        $result = $client->terms->acceptTerms('pending_token_123', 'signature_hash_123', LanguageEnum::EN_US);

        $this->assertInstanceOf(AccessTokenDto::class, $result);
        $this->assertEquals('access_token_jwt_token', $result->token);
    }

    public function testRevokePendingToken(): void
    {
        $mockJson = json_encode(['item' => null]);

        $client = $this->createMockClientWithResponse($mockJson);
        $result = $client->accessToken->revokePendingToken('pending_token_123');

        $this->assertInstanceOf(AccessTokenDto::class, $result);
        $this->assertNull($result->token);
    }

    public function testExchangeWithOidc(): void
    {
        $mockJson = json_encode([
            'item' => 'access_token_oidc_jwt',
        ]);

        $client = $this->createMockClientWithResponse($mockJson);
        $result = $client->accessToken->exchangeWithOidc('oidc_code_123', 'nonce_123');

        $this->assertInstanceOf(AccessTokenDto::class, $result);
        $this->assertEquals('access_token_oidc_jwt', $result->token);
    }
}
