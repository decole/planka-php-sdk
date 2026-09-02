<?php

declare(strict_types=1);

namespace Planka\Bridge\Tests\Unit;

use Planka\Bridge\Views\Dto\Board\BoardListDto;
use Planka\Bridge\Views\Dto\List\ListDto;
use Planka\Bridge\Views\Factory\List\ListDtoFactory;

final class BoardListTest extends AbstractUnitTestCase
{
    public function testCreateList(): void
    {
        $client = $this->createMockClient('BoardList/list_create.json');
        $list = $client->boardList->create('1854744330917381674', 'To Do', 1);

        $this->assertInstanceOf(BoardListDto::class, $list);
        $this->assertNotEmpty($list->id);
        $this->assertEquals('[v2-test] To Do', $list->name);
    }

    public function testCreateListDtoFromRawPayload(): void
    {
        $payload = [
            'id' => '1853653637654381600',
            'createdAt' => '2026-08-31T08:18:53.214Z',
            'updatedAt' => null,
            'type' => 'active',
            'position' => 65536,
            'name' => 'Backlog',
            'color' => null,
            'boardId' => '1853651689878324236',
        ];

        $list = (new ListDtoFactory())->create($payload);

        $this->assertInstanceOf(ListDto::class, $list);
        $this->assertEquals('1853653637654381600', $list->id);
        $this->assertEquals('Backlog', $list->name);
        $this->assertEquals('1853651689878324236', $list->boardId);
        $this->assertEquals(65536, $list->position);
    }
}
