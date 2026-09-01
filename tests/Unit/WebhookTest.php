<?php

declare(strict_types=1);

namespace Planka\Bridge\Tests\Unit;

use Planka\Bridge\Views\Dto\Webhook\WebhookDto;

final class WebhookTest extends AbstractUnitTestCase
{
    public function testCreateWebhook(): void
    {
        $client = $this->createMockClient('Webhook/webhook_create.json');
        $webhook = $client->webhook->create('Test Webhook v2', 'https://example.com/webhook', 'cardCreate');

        $this->assertInstanceOf(WebhookDto::class, $webhook);
        $this->assertNotEmpty($webhook->id);
        $this->assertEquals('Test Webhook v2', $webhook->name);
    }

    public function testListWebhooks(): void
    {
        $client = $this->createMockClient('Webhook/webhook_list.json');
        $webhooks = $client->webhook->list();

        $this->assertIsArray($webhooks);
        $this->assertNotEmpty($webhooks);
        $this->assertInstanceOf(WebhookDto::class, $webhooks[0]);
    }

    public function testUpdateWebhook(): void
    {
        $client = $this->createMockClient('Webhook/webhook_update.json');
        $webhook = $client->webhook->update('1854744334750975547', name: 'Updated Webhook v2');

        $this->assertInstanceOf(WebhookDto::class, $webhook);
        $this->assertEquals('Updated Webhook v2', $webhook->name);
    }
}
