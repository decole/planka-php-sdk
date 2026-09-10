<?php

declare(strict_types=1);

namespace Planka\Bridge\Tests\Unit;

use Planka\Bridge\Exceptions\ValidateException;
use Planka\Bridge\Views\Dto\Project\ProjectManagerDto;

final class ProjectManagerTest extends AbstractUnitTestCase
{
    public function testAddManagerSuccess(): void
    {
        $payload = json_encode([
            'item' => [
                'id' => 'pm_123',
                'projectId' => 'proj_123',
                'userId' => 'user_123',
                'createdAt' => '2026-08-31T07:54:19.894Z',
                'updatedAt' => null,
            ],
        ]);

        $client = $this->createMockClientWithResponse($payload, 200);
        $manager = $client->projectManager()->add('proj_123', 'user_123');

        $this->assertInstanceOf(ProjectManagerDto::class, $manager);
        $this->assertEquals('pm_123', $manager->id);
        $this->assertEquals('proj_123', $manager->projectId);
        $this->assertEquals('user_123', $manager->userId);
    }

    public function testAddManagerConflict409ThrowsValidateException(): void
    {
        $client = $this->createMockClientWithResponse(
            body: json_encode(['message' => 'User is already a project manager']),
            statusCode: 409,
        );

        $this->expectException(ValidateException::class);
        $this->expectExceptionMessage('User already joined to project managers');
        $this->expectExceptionCode(409);

        $client->projectManager()->add('proj_123', 'user_123');
    }
}
