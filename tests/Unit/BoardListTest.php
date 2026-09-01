<?php

declare(strict_types=1);

namespace Planka\Bridge\Tests\Unit;

use Planka\Bridge\Views\Dto\Board\BoardListDto;

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
}
