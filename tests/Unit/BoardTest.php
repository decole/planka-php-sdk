<?php

declare(strict_types=1);

namespace Planka\Bridge\Tests\Unit;

use Planka\Bridge\Views\Dto\Board\BoardDto;
use Planka\Bridge\Views\Dto\Card\CardActionListDto;

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

    public function testGetActions(): void
    {
        $mockJson = json_encode([
            'items' => [
                [
                    'id' => '123456789',
                    'boardId' => '1854744330917381674',
                    'cardId' => 'card123',
                    'userId' => 'user123',
                    'type' => 'createCard',
                    'data' => [],
                    'createdAt' => '2026-09-01T00:00:00.000Z',
                    'updatedAt' => null,
                ],
            ],
            'included' => [
                'users' => [],
            ],
        ]);

        $client = $this->createMockClientWithResponse($mockJson);
        $actions = $client->board->getActions('1854744330917381674');

        $this->assertInstanceOf(CardActionListDto::class, $actions);
        $this->assertNotEmpty($actions->items);
    }
}
