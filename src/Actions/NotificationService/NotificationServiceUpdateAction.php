<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\NotificationService;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Enum\NotificationServiceFormatEnum;
use Planka\Bridge\Traits\NotificationServiceHydrateTrait;

final class NotificationServiceUpdateAction implements ActionInterface, ResponseResultInterface
{
    use NotificationServiceHydrateTrait;

    private array $options = [];

    public function __construct(
        private readonly string $id,
        ?string $url = null,
        ?NotificationServiceFormatEnum $format = null,
    ) {
        $body = [];

        if (null !== $url) {
            $body['url'] = $url;
        }

        if (null !== $format) {
            $body['format'] = $format->value;
        }

        $this->options['json'] = $body;
    }

    public function url(): string
    {
        return "api/notification-services/{$this->id}";
    }

    public function getOptions(): array
    {
        return $this->options;
    }
}
