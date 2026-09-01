<?php

declare(strict_types=1);

namespace Planka\Bridge\Tests\Unit;

use Planka\Bridge\Views\Dto\Card\CardDto;

final class CardTest extends AbstractUnitTestCase
{
    public function testCreateCard(): void
    {
        $client = $this->createMockClient('Card/card_create.json');
        $card = $client->card->create('1854744331521361455', 'Task 1', 1);

        $this->assertInstanceOf(CardDto::class, $card);
        $this->assertNotEmpty($card->id);
        $this->assertEquals('[v2-test] Task 1', $card->name);
    }

    public function testDuplicateCard(): void
    {
        $client = $this->createMockClient('Card/card_duplicate.json');
        $card = $client->card->duplicate('1854744332452496946');

        $this->assertInstanceOf(CardDto::class, $card);
        $this->assertNotEmpty($card->id);
    }
}
