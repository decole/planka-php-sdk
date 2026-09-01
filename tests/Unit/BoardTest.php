<?php

declare(strict_types=1);

namespace Planka\Bridge\Tests\Unit;

use Planka\Bridge\Views\Dto\Board\BoardDto;

final class BoardTest extends AbstractUnitTestCase
{
    public function testCreateBoard(): void
    {
        $client = $this->createMockClient('Board/board_create.json');
        $board = $client->board->create('1854744330170795558', 'Fixture Board', 0);

        $this->assertInstanceOf(BoardDto::class, $board);
        $this->assertNotNull($board->item);
        $this->assertNotEmpty($board->item->id);
    }

    public function testUpdateBoard(): void
    {
        $client = $this->createMockClient('Board/board_update.json');
        $updated = $client->board->update('1854744330917381674', 'Board Updated');

        $this->assertInstanceOf(BoardDto::class, $updated);
    }
}
