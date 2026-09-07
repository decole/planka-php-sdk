<?php

declare(strict_types=1);

namespace Planka\Bridge\Tests\Unit;

use Planka\Bridge\Controllers\Board;
use Planka\Bridge\Controllers\Card;
use Planka\Bridge\Controllers\Project;
use Planka\Bridge\Views\Dto\Board\BoardDto;
use Planka\Bridge\Views\Dto\Card\CardDto;
use Planka\Bridge\Views\Dto\Project\ProjectDto;

final class PlankaClientLazyLoadingAndPatchingTest extends AbstractUnitTestCase
{
    public function testLazyLoadingControllers(): void
    {
        $client = $this->createMockClientWithResponse('{"item": {}}');

        // Test property read lazy loading & caching
        $boardController1 = $client->board;
        $boardController2 = $client->board;

        $this->assertInstanceOf(Board::class, $boardController1);
        $this->assertSame($boardController1, $boardController2, 'Controller instance must be cached');

        $cardController = $client->card;
        $this->assertInstanceOf(Card::class, $cardController);

        $projectController = $client->project;
        $this->assertInstanceOf(Project::class, $projectController);
    }

    public function testInvalidControllerThrowsException(): void
    {
        $client = $this->createMockClientWithResponse('{}');

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Controller 'invalidController' does not exist on PlankaClient.");

        /* @psalm-suppress UndefinedPropertyFetch */
        $client->invalidController;
    }

    public function testCardPatching(): void
    {
        $client = $this->createMockClient('Card/card_create.json');
        $card = $client->card->patching('1854744331521361455', ['name' => 'Patched Card Name']);

        $this->assertInstanceOf(CardDto::class, $card);
        $this->assertNotEmpty($card->id);
    }

    public function testBoardPatching(): void
    {
        $client = $this->createMockClient('Board/board_get.json');
        $board = $client->board->patching('1854744331521361455', ['name' => 'Patched Board Name']);

        $this->assertInstanceOf(BoardDto::class, $board);
        $this->assertNotNull($board->item);
    }

    public function testProjectPatching(): void
    {
        $client = $this->createMockClient('Project/project_get.json');
        $project = $client->project->patching('1854744331521361455', ['name' => 'Patched Project Name']);

        $this->assertInstanceOf(ProjectDto::class, $project);
        $this->assertNotEmpty($project->id);
    }
}
