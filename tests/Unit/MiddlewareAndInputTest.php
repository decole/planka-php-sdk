<?php

declare(strict_types=1);

namespace Planka\Bridge\Tests\Unit;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Enum\BackgroundGradientEnum;
use Planka\Bridge\Enum\BackgroundTypeEnum;
use Planka\Bridge\Enum\BoardDefaultCardTypeEnum;
use Planka\Bridge\Enum\BoardDefaultViewEnum;
use Planka\Bridge\Enum\ProjectTypeEnum;
use Planka\Bridge\Inputs\BoardCreateInput;
use Planka\Bridge\Inputs\CardCreateInput;
use Planka\Bridge\Inputs\ProjectCreateInput;
use Planka\Bridge\PlankaClient;
use Planka\Bridge\TransportClients\Middleware\TransportMiddlewareInterface;
use Planka\Bridge\TransportClients\TransportClientInterface;
use Planka\Bridge\Views\Dto\Board\BoardDto;
use Planka\Bridge\Views\Dto\Card\CardDto;
use Planka\Bridge\Views\Dto\Project\ProjectDto;

final class MiddlewareAndInputTest extends AbstractUnitTestCase
{
    public function testMiddlewareExecutionOrder(): void
    {
        $log = [];

        $middleware1 = new class ($log) implements TransportMiddlewareInterface {
            public function __construct(private array &$log) {}

            public function handle(ActionInterface $action, string $method, callable $next): mixed
            {
                $this->log[] = 'm1_before';
                $result = $next($action, $method);
                $this->log[] = 'm1_after';

                return $result;
            }
        };

        $middleware2 = new class ($log) implements TransportMiddlewareInterface {
            public function __construct(private array &$log) {}

            public function handle(ActionInterface $action, string $method, callable $next): mixed
            {
                $this->log[] = 'm2_before';
                $result = $next($action, $method);
                $this->log[] = 'm2_after';

                return $result;
            }
        };

        $mockTransport = $this->createMock(TransportClientInterface::class);
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
            middlewares: [$middleware1, $middleware2],
        );

        $project = $client->project()->get('proj123');

        $this->assertInstanceOf(ProjectDto::class, $project);
        $this->assertEquals(['m1_before', 'm2_before', 'm2_after', 'm1_after'], $log);
    }

    public function testProjectCreateInputDto(): void
    {
        $client = $this->createMockClient('Project/project_create.json');
        $project = $client->project()->create(new ProjectCreateInput(
            name: 'New Project',
            type: ProjectTypeEnum::PRIVATE,
            backgroundType: BackgroundTypeEnum::GRADIENT,
            backgroundGradient: BackgroundGradientEnum::OCEAN_DIVE,
        ));

        $this->assertInstanceOf(ProjectDto::class, $project);
        $this->assertNotEmpty($project->id);
    }

    public function testBoardCreateInputDto(): void
    {
        $client = $this->createMockClient('Board/board_create.json');
        $board = $client->board()->create(
            projectId: 'proj123',
            nameOrInput: new BoardCreateInput(
                name: 'New Board',
                defaultView: BoardDefaultViewEnum::KANBAN,
            ),
        );

        $this->assertInstanceOf(BoardDto::class, $board);
        $this->assertNotNull($board->item);
    }

    public function testCardCreateInputDto(): void
    {
        $client = $this->createMockClient('Card/card_create.json');
        $card = $client->card()->create(
            listId: 'list123',
            nameOrInput: new CardCreateInput(
                name: 'New Card',
                type: BoardDefaultCardTypeEnum::PROJECT,
            ),
        );

        $this->assertInstanceOf(CardDto::class, $card);
        $this->assertNotEmpty($card->id);
    }
}
