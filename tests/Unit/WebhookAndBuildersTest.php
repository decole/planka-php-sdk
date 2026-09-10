<?php

declare(strict_types=1);

namespace Planka\Bridge\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Planka\Bridge\Builders\BoardListBuilder;
use Planka\Bridge\Builders\UserBuilder;
use Planka\Bridge\Builders\WebhookBuilder;
use Planka\Bridge\Enum\ListColorEnum;
use Planka\Bridge\Enum\ListTypeEnum;
use Planka\Bridge\Enum\UserRoleEnum;
use Planka\Bridge\Webhook\WebhookEventDispatcher;
use Planka\Bridge\Webhook\WebhookEventDto;
use Planka\Bridge\Webhook\WebhookParser;

final class WebhookAndBuildersTest extends TestCase
{
    public function testWebhookSignatureVerification(): void
    {
        $parser = new WebhookParser();
        $payload = '{"action":{"type":"cardCreate"}}';
        $secret = 'super_secret_webhook_key';

        $signature = hash_hmac('sha256', $payload, $secret);

        $this->assertTrue($parser->verifySignature($payload, $secret, $signature));
        $this->assertTrue($parser->verifySignature($payload, $secret, "sha256={$signature}"));
        $this->assertFalse($parser->verifySignature($payload, $secret, 'invalid_signature'));
    }

    public function testWebhookEventDispatcher(): void
    {
        $dispatcher = new WebhookEventDispatcher();
        $payload = '{"action":{"type":"cardCreate"}}';

        $receivedEvent = null;
        $event = $dispatcher->dispatch($payload, function (WebhookEventDto $e) use (&$receivedEvent) {
            $receivedEvent = $e;
        });

        $this->assertSame($event, $receivedEvent);
        $this->assertEquals('cardCreate', $event->eventType);
    }

    public function testBuilders(): void
    {
        $userBuilder = new UserBuilder('Alice');
        $userPatch = $userBuilder
            ->setEmail('alice@example.com')
            ->setRole(UserRoleEnum::ADMIN)
            ->setIsDeactivated(false)
            ->build();

        $this->assertEquals('Alice', $userPatch->name);
        $this->assertEquals('alice@example.com', $userPatch->email);
        $this->assertEquals(UserRoleEnum::ADMIN, $userPatch->role);
        $this->assertFalse($userPatch->isDeactivated);

        $boardListBuilder = new BoardListBuilder('Testing');
        $listPatch = $boardListBuilder
            ->setPosition(5)
            ->setType(ListTypeEnum::ACTIVE)
            ->setColor(ListColorEnum::ORANGE_PEEL)
            ->build();

        $this->assertEquals('Testing', $listPatch->name);
        $this->assertEquals(5, $listPatch->position);
        $this->assertEquals(ListTypeEnum::ACTIVE, $listPatch->type);
        $this->assertEquals(ListColorEnum::ORANGE_PEEL, $listPatch->color);

        $webhookBuilder = new WebhookBuilder('Discord Hook');
        $webhookPatch = $webhookBuilder
            ->setUrl('https://discord.com/api/webhooks/xxx')
            ->setAccessToken('secret123')
            ->setEvents(['cardCreate'])
            ->build();

        $this->assertEquals('Discord Hook', $webhookPatch->name);
        $this->assertEquals('https://discord.com/api/webhooks/xxx', $webhookPatch->url);
        $this->assertEquals('secret123', $webhookPatch->accessToken);
        $this->assertEquals(['cardCreate'], $webhookPatch->events);
    }
}
