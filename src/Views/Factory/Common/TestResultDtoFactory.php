<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\Common;

use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Dto\Common\TestResultDto;

final class TestResultDtoFactory implements OutputInterface
{
    /**
     * @param array<string, mixed> $data
     */
    public function create(array $data): TestResultDto
    {
        return new TestResultDto(
            success: (bool) ($data['success'] ?? true),
            message: isset($data['message']) && is_string($data['message']) ? $data['message'] : null,
            _rawResponse: $data,
        );
    }
}
