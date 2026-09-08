<?php

declare(strict_types=1);

namespace Planka\Bridge\Tests\Unit;

use Planka\Bridge\Controllers\Board;
use Planka\Bridge\Controllers\Card;
use Planka\Bridge\Controllers\Project;
use Planka\Bridge\Inputs\BoardPatchInput;
use Planka\Bridge\Inputs\CardPatchInput;
use Planka\Bridge\Inputs\ProjectPatchInput;
use Planka\Bridge\Views\Dto\Board\BoardDto;
use Planka\Bridge\Views\Dto\Card\CardDto;
use Planka\Bridge\Views\Dto\Project\ProjectDto;

final class PlankaClientLazyLoadingAndPatchingTest extends AbstractUnitTestCase
{
    public function testLazyLoadingControllers(): void
    {
        $client = $this->createMockClientWithResponse('{"item": {}}');

        // Test method-based lazy loading & caching
        $boardMethod1 = $client->board();
        $boardMethod2 = $client->board();
        $this->assertInstanceOf(Board::class, $boardMethod1);
        $this->assertSame($boardMethod1, $boardMethod2);

        $cardController = $client->card();
        $this->assertInstanceOf(Card::class, $cardController);

        $projectController = $client->project();
        $this->assertInstanceOf(Project::class, $projectController);
    }

    public function testCardPatching(): void
    {
        $client = $this->createMockClient('Card/card_create.json');
        $card = $client->card()->patching('1854744331521361455', new CardPatchInput(name: 'Patched Card Name'));

        $this->assertInstanceOf(CardDto::class, $card);
        $this->assertNotEmpty($card->id);
    }

    public function testBoardPatching(): void
    {
        $client = $this->createMockClient('Board/board_get.json');
        $board = $client->board()->patching('1854744331521361455', new BoardPatchInput(name: 'Patched Board Name'));

        $this->assertInstanceOf(BoardDto::class, $board);
        $this->assertNotNull($board->item);
    }

    public function testProjectPatching(): void
    {
        $client = $this->createMockClient('Project/project_get.json');
        $project = $client->project()->patching('1854744331521361455', new ProjectPatchInput(name: 'Patched Project Name'));

        $this->assertInstanceOf(ProjectDto::class, $project);
        $this->assertNotEmpty($project->id);
    }
}
