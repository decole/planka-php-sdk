<?php

declare(strict_types=1);

namespace Planka\Bridge\Tests\Unit;

use Planka\Bridge\Enum\LanguageEnum;

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
        $terms = $client->getTerms(LanguageEnum::EN_US);

        $this->assertIsArray($terms);
        $this->assertEquals('en-US', $terms['item']['language']);
    }

    public function testAcceptTerms(): void
    {
        $mockJson = json_encode([
            'item' => 'access_token_jwt_token',
        ]);

        $client = $this->createMockClientWithResponse($mockJson);
        $result = $client->acceptTerms('pending_token_123', 'signature_hash_123', LanguageEnum::EN_US);

        $this->assertIsArray($result);
        $this->assertEquals('access_token_jwt_token', $result['item']);
    }

    public function testRevokePendingToken(): void
    {
        $mockJson = json_encode(['item' => null]);

        $client = $this->createMockClientWithResponse($mockJson);
        $result = $client->revokePendingToken('pending_token_123');

        $this->assertIsArray($result);
    }

    public function testExchangeWithOidc(): void
    {
        $mockJson = json_encode([
            'item' => 'access_token_oidc_jwt',
        ]);

        $client = $this->createMockClientWithResponse($mockJson);
        $result = $client->exchangeWithOidc('oidc_code_123', 'nonce_123');

        $this->assertIsArray($result);
        $this->assertEquals('access_token_oidc_jwt', $result['item']);
    }
}
