<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\Webhook;

use Planka\Bridge\Contracts\Dto\OutputDtoInterface;

class WebhookDto implements OutputDtoInterface
{
    public function __construct(
        public readonly string $id,
        public string $name,
        public string $url,
        public ?string $accessToken,
        /** @var list<string>|null */
        public ?array $events,
        /** @var list<string>|null */
        public ?array $excludedEvents,
        public ?\DateTimeImmutable $createdAt,
        public ?\DateTimeImmutable $updatedAt,
    ) {}
}
