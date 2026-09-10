<?php

declare(strict_types=1);

namespace Planka\Bridge\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Planka\Bridge\Enum\ListColorEnum;
use Planka\Bridge\Enum\ListTypeEnum;
use Planka\Bridge\Enum\UserRoleEnum;
use Planka\Bridge\Inputs\AttachmentPatchInput;
use Planka\Bridge\Inputs\BaseCustomFieldGroupPatchInput;
use Planka\Bridge\Inputs\BoardListPatchInput;
use Planka\Bridge\Inputs\CardTaskPatchInput;
use Planka\Bridge\Inputs\CustomFieldGroupPatchInput;
use Planka\Bridge\Inputs\CustomFieldPatchInput;
use Planka\Bridge\Inputs\PatchInputNormalizer;
use Planka\Bridge\Inputs\TaskListPatchInput;
use Planka\Bridge\Inputs\UserPatchInput;
use Planka\Bridge\Inputs\WebhookPatchInput;
use Planka\Bridge\Views\Dto\Project\ProjectDto;

final class PatchInputsAndDtoTraitTest extends TestCase
{
    public function testUserPatchInput(): void
    {
        $input = new UserPatchInput(
            name: 'John Doe',
            role: UserRoleEnum::PROJECT_OWNER,
            isDeactivated: false,
        );

        $array = $input->toArray();
        $this->assertEquals([
            'name' => 'John Doe',
            'role' => 'projectOwner',
            'isDeactivated' => false,
        ], $array);

        $normalized = PatchInputNormalizer::normalize($input);
        $this->assertEquals([
            'name' => 'John Doe',
            'role' => 'projectOwner',
            'isDeactivated' => false,
        ], $normalized);
    }

    public function testBoardListPatchInput(): void
    {
        $input = new BoardListPatchInput(
            name: 'In Progress',
            position: 2,
            type: ListTypeEnum::ACTIVE,
            color: ListColorEnum::ORANGE_PEEL,
        );

        $this->assertEquals([
            'name' => 'In Progress',
            'position' => 2,
            'type' => 'active',
            'color' => 'orange-peel',
        ], $input->toArray());
    }

    public function testWebhookPatchInput(): void
    {
        $input = new WebhookPatchInput(
            name: 'Slack Hook',
            url: 'https://hooks.slack.com/services/xxx',
            events: ['cardCreate', 'cardUpdate'],
        );

        $this->assertEquals([
            'name' => 'Slack Hook',
            'url' => 'https://hooks.slack.com/services/xxx',
            'events' => ['cardCreate', 'cardUpdate'],
        ], $input->toArray());
    }

    public function testAttachmentPatchInput(): void
    {
        $input = new AttachmentPatchInput(name: 'renamed_attachment.pdf');
        $this->assertEquals(['name' => 'renamed_attachment.pdf'], $input->toArray());
    }

    public function testTaskListPatchInput(): void
    {
        $input = new TaskListPatchInput(
            name: 'Todo Checklist',
            position: 10,
            showOnFrontOfCard: true,
            hideCompletedTasks: false,
        );

        $this->assertEquals([
            'name' => 'Todo Checklist',
            'position' => 10,
            'showOnFrontOfCard' => true,
            'hideCompletedTasks' => false,
        ], $input->toArray());
    }

    public function testCardTaskPatchInput(): void
    {
        $input = new CardTaskPatchInput(
            name: 'Write unit tests',
            isCompleted: true,
            assigneeUserId: 'user_123',
        );

        $this->assertEquals([
            'name' => 'Write unit tests',
            'isCompleted' => true,
            'assigneeUserId' => 'user_123',
        ], $input->toArray());
    }

    public function testCustomFieldPatchInputs(): void
    {
        $fieldInput = new CustomFieldPatchInput(
            name: 'Story Points',
            position: 1,
            showOnFrontOfCard: true,
        );
        $this->assertEquals([
            'name' => 'Story Points',
            'position' => 1,
            'showOnFrontOfCard' => true,
        ], $fieldInput->toArray());

        $groupInput = new CustomFieldGroupPatchInput(name: 'Scrum Metrics', position: 2);
        $this->assertEquals(['name' => 'Scrum Metrics', 'position' => 2], $groupInput->toArray());

        $baseGroupInput = new BaseCustomFieldGroupPatchInput(name: 'Global Fields');
        $this->assertEquals(['name' => 'Global Fields'], $baseGroupInput->toArray());
    }

    public function testOutputDtoTraitToArrayReflectsMutations(): void
    {
        $now = new \DateTimeImmutable('2026-09-01T12:00:00.000Z');
        $project = new ProjectDto(
            id: 'proj_1',
            createdAt: $now,
            updatedAt: null,
            name: 'Original Name',
            _rawResponse: ['id' => 'proj_1', 'name' => 'Original Name'],
        );

        $project->name = 'Mutated Name';
        $array = $project->toArray();

        $this->assertEquals('Mutated Name', $array['name']);
        $this->assertArrayNotHasKey('_rawResponse', $array);
        $this->assertEquals('2026-09-01T12:00:00.000Z', $array['createdAt']);
    }
}
