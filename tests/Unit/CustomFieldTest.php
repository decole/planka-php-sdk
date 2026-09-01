<?php

declare(strict_types=1);

namespace Planka\Bridge\Tests\Unit;

use Planka\Bridge\Views\Dto\CustomField\BaseCustomFieldGroupDto;
use Planka\Bridge\Views\Dto\CustomField\CustomFieldDto;
use Planka\Bridge\Views\Dto\CustomField\CustomFieldGroupDto;

final class CustomFieldTest extends AbstractUnitTestCase
{
    public function testCreateBaseCustomFieldGroup(): void
    {
        $client = $this->createMockClient('CustomField/base_group_create.json');
        $group = $client->baseCustomFieldGroup->create('1854744330170795558', 'Base Specs');

        $this->assertInstanceOf(BaseCustomFieldGroupDto::class, $group);
        $this->assertNotEmpty($group->id);
        $this->assertEquals('Base Specs', $group->name);
    }

    public function testCreateCustomFieldInBaseGroup(): void
    {
        $client = $this->createMockClient('CustomField/custom_field_create.json');
        $field = $client->customField->createInBaseGroup('1854744330464396840', 'Priority', showOnFrontOfCard: true);

        $this->assertInstanceOf(CustomFieldDto::class, $field);
        $this->assertNotEmpty($field->id);
        $this->assertEquals('Priority', $field->name);
    }

    public function testCreateBoardCustomFieldGroup(): void
    {
        $client = $this->createMockClient('CustomField/board_group_create.json');
        $group = $client->customFieldGroup->createInBoard('1854744330917381674', 'Board Fields');

        $this->assertInstanceOf(CustomFieldGroupDto::class, $group);
        $this->assertNotEmpty($group->id);
        $this->assertEquals('Board Fields', $group->name);
    }
}
