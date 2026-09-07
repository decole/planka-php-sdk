<?php

declare(strict_types=1);

namespace Planka\Bridge\Tests\Unit;

use Planka\Bridge\Views\Dto\Background\BackgroundImageDto;

final class ProjectBackgroundImageTest extends AbstractUnitTestCase
{
    public function testDeleteBackgroundImage(): void
    {
        $mockJson = json_encode([
            'item' => [
                'id' => 'img123',
                'projectId' => 'proj123',
                'size' => 1024,
                'url' => 'https://example.com/bg.jpg',
            ],
        ]);

        $client = $this->createMockClientWithResponse($mockJson);
        $result = $client->project()->deleteBackgroundImage('img123');

        $this->assertInstanceOf(BackgroundImageDto::class, $result);
        $this->assertEquals('img123', $result->id);
    }
}
