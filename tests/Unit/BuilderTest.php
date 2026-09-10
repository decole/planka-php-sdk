<?php

declare(strict_types=1);

namespace Planka\Bridge\Tests\Unit;

use Planka\Bridge\Enum\BackgroundGradientEnum;
use Planka\Bridge\Enum\BackgroundTypeEnum;
use Planka\Bridge\Enum\BoardDefaultCardTypeEnum;
use Planka\Bridge\Enum\BoardDefaultViewEnum;
use Planka\Bridge\Enum\ProjectTypeEnum;
use Planka\Bridge\Inputs\BoardCreateInput;
use Planka\Bridge\Inputs\CardCreateInput;
use Planka\Bridge\Inputs\ProjectCreateInput;
use Planka\Bridge\Views\Dto\Board\BoardDto;
use Planka\Bridge\Views\Dto\Card\CardDto;
use Planka\Bridge\Views\Dto\Project\ProjectDto;

final class BuilderTest extends AbstractUnitTestCase
{
    public function testProjectBuilderFluentApi(): void
    {
        $client = $this->createMockClient('Project/project_create.json');

        $builder = $client->project()->builder('Fluent Project')
            ->setType(ProjectTypeEnum::SHARED)
            ->setDescription('Created via Fluent Builder')
            ->setBackgroundType(BackgroundTypeEnum::GRADIENT)
            ->setBackgroundGradient(BackgroundGradientEnum::OCEAN_DIVE);

        $input = $builder->build();
        $this->assertInstanceOf(ProjectCreateInput::class, $input);
        $this->assertEquals('Fluent Project', $input->name);

        $project = $client->project()->create($builder);
        $this->assertInstanceOf(ProjectDto::class, $project);
    }

    public function testBoardBuilderFluentApi(): void
    {
        $client = $this->createMockClient('Board/board_create.json');

        $builder = $client->board()->builder('Fluent Board')
            ->setPosition(100)
            ->setDefaultView(BoardDefaultViewEnum::KANBAN)
            ->setDefaultCardType(BoardDefaultCardTypeEnum::PROJECT);

        $input = $builder->build();
        $this->assertInstanceOf(BoardCreateInput::class, $input);
        $this->assertEquals('Fluent Board', $input->name);

        $board = $client->board()->create('proj123', $builder);
        $this->assertInstanceOf(BoardDto::class, $board);
    }

    public function testCardBuilderFluentApi(): void
    {
        $client = $this->createMockClient('Card/card_create.json');

        $builder = $client->card()->builder('Fluent Card')
            ->setPosition(1)
            ->setDescription('Detailed task description')
            ->setDueDate(new \DateTimeImmutable('2026-10-01T00:00:00.000Z'))
            ->setIsDueCompleted(false)
            ->setType(BoardDefaultCardTypeEnum::PROJECT);

        $input = $builder->build();
        $this->assertInstanceOf(CardCreateInput::class, $input);
        $this->assertEquals('Fluent Card', $input->name);

        $card = $client->card()->create('list123', $builder);
        $this->assertInstanceOf(CardDto::class, $card);
    }
}
