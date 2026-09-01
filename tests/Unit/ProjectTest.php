<?php

declare(strict_types=1);

namespace Planka\Bridge\Tests\Unit;

use Planka\Bridge\Views\Dto\Project\ProjectDto;

final class ProjectTest extends AbstractUnitTestCase
{
    public function testCreateProject(): void
    {
        $client = $this->createMockClient('Project/project_create.json');
        $project = $client->project->create('Test Project');

        $this->assertInstanceOf(ProjectDto::class, $project);
        $this->assertNotEmpty($project->id);
        $this->assertStringContainsString('Project', $project->name);
    }

    public function testUpdateProject(): void
    {
        $client = $this->createMockClient('Project/project_update.json');

        $projectDto = new ProjectDto(
            id: '1854744306934351389',
            createdAt: new \DateTimeImmutable(),
            updatedAt: new \DateTimeImmutable(),
            name: 'Updated Project',
            background: null,
            backgroundImage: null,
        );

        $updatedProject = $client->project->update($projectDto);

        $this->assertInstanceOf(ProjectDto::class, $updatedProject);
        $this->assertEquals($projectDto->id, $updatedProject->id);
    }
}
