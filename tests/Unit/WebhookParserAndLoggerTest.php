<?php

declare(strict_types=1);

namespace Planka\Bridge\Tests\Unit;

use Planka\Bridge\Exceptions\PlankaHydrationException;
use Planka\Bridge\PlankaClient;
use Planka\Bridge\TransportClients\Middleware\PsrLoggerMiddleware;
use Planka\Bridge\Views\Dto\Project\ProjectDto;
use Planka\Bridge\Views\Factory\Project\ProjectDtoFactory;
use Planka\Bridge\Webhook\WebhookEventDto;
use Planka\Bridge\Webhook\WebhookParser;

final class WebhookParserAndLoggerTest extends AbstractUnitTestCase
{
    public function testWebhookParserParseCardCreate(): void
    {
        $json = json_encode([
            'event' => 'cardCreate',
            'card' => [
                'id' => 'card123',
                'createdAt' => '2026-09-09T00:00:00.000Z',
                'name' => 'Test Webhook Card',
                'boardId' => 'board123',
                'listId' => 'list123',
            ],
        ]);

        $parser = new WebhookParser();
        $event = $parser->parse($json);

        $this->assertInstanceOf(WebhookEventDto::class, $event);
        $this->assertEquals('cardCreate', $event->eventType);
        $this->assertTrue($event->isCardCreated());
        $this->assertNotNull($event->card);
        $this->assertEquals('card123', $event->card->id);
    }

    public function testPsrLoggerMiddleware(): void
    {
        $logs = [];
        $mockLogger = new class ($logs) implements \Psr\Log\LoggerInterface {
            use \Psr\Log\LoggerTrait;

            public function __construct(private array &$logs) {}

            public function log($level, $message, array $context = []): void
            {
                $this->logs[] = (string) $message;
            }
        };

        $mockTransport = $this->createMock(\Planka\Bridge\TransportClients\TransportClientInterface::class);
        $mockTransport->expects($this->once())
            ->method('get')
            ->willReturn(new ProjectDto(
                id: 'proj123',
                createdAt: new \DateTimeImmutable(),
                updatedAt: null,
                name: 'Project Mock',
            ));

        $client = new PlankaClient(
            config: $this->config,
            client: $mockTransport,
            middlewares: [new PsrLoggerMiddleware($mockLogger)],
        );

        $project = $client->project()->get('proj123');

        $this->assertInstanceOf(ProjectDto::class, $project);
        $this->assertNotEmpty($logs);
        $this->assertStringContainsString('[Planka SDK]', $logs[0]);
    }

    public function testPlankaHydrationException(): void
    {
        $factory = new ProjectDtoFactory();

        $this->expectException(PlankaHydrationException::class);
        $this->expectExceptionMessage('Failed to hydrate ProjectDto');

        $factory->create(['invalid' => 'payload_without_id']);
    }
}
