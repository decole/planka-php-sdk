<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\NotificationService;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Enum\NotificationServiceFormatEnum;
use Planka\Bridge\Traits\NotificationServiceHydrateTrait;

final class NotificationServiceCreateInBoardAction implements ActionInterface, ResponseResultInterface
{
    use NotificationServiceHydrateTrait;

    private array $options = [];

    public function __construct(
        private readonly string $boardId,
        string $url,
        NotificationServiceFormatEnum $format,
    ) {
        $this->options['json'] = [
            'url' => $url,
            'format' => $format->value,
        ];
    }

    public function url(): string
    {
        return "api/boards/{$this->boardId}/notification-services";
    }

    public function getOptions(): array
    {
        return $this->options;
    }
}
