<?php

declare(strict_types=1);

namespace Planka\Bridge\Tests\Unit;

use Planka\Bridge\Views\Dto\Card\CardActionListDto;

final class BoardActionTest extends AbstractUnitTestCase
{
    public function testGetBoardActions(): void
    {
        $mockJson = json_encode([
            'items' => [
                [
                    'id' => '123456789',
                    'boardId' => 'board123',
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
        $actions = $client->cardAction->getBoardActions('board123');

        $this->assertInstanceOf(CardActionListDto::class, $actions);
        $this->assertNotEmpty($actions->items);
    }
}
