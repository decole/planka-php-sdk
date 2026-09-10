<?php

declare(strict_types=1);

namespace Planka\Bridge\Contracts\Resources;

use Planka\Bridge\Enum\NotificationServiceFormatEnum;
use Planka\Bridge\Views\Dto\Common\TestResultDto;
use Planka\Bridge\Views\Dto\NotificationService\NotificationServiceDto;

interface NotificationServiceResourceInterface
{
    public function createInBoard(
        string $boardId,
        string $url,
        NotificationServiceFormatEnum $format,
    ): NotificationServiceDto;

    public function createInUser(
        string $userId,
        string $url,
        NotificationServiceFormatEnum $format,
    ): NotificationServiceDto;

    public function update(
        string $id,
        ?string $url = null,
        ?NotificationServiceFormatEnum $format = null,
    ): NotificationServiceDto;

    /**
     * @param array{url?: string, format?: 'text'|'markdown'|'html'|NotificationServiceFormatEnum} $map
     */
    public function patching(string $id, array $map): NotificationServiceDto;

    public function delete(string $id): NotificationServiceDto;

    public function test(string $id): TestResultDto;
}
