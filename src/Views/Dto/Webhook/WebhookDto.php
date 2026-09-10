<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\Webhook;

use Planka\Bridge\Contracts\Dto\OutputDtoInterface;
use Planka\Bridge\Traits\OutputDtoTrait;

final class WebhookDto implements OutputDtoInterface
{
    use OutputDtoTrait;

    public function __construct(
        public readonly string $id,
        public string $name,
        public string $url,
        public ?string $accessToken,
        /** @var list<string>|null */
        public ?array $events,
        /** @var list<string>|null */
        public ?array $excludedEvents,
        public ?string $boardId = null,
        public ?string $projectManagerId = null,
        public ?\DateTimeImmutable $createdAt = null,
        public ?\DateTimeImmutable $updatedAt = null,
        /** @var array<string, mixed> Diagnostic raw response array from Planka API to verify DTO field hydration. */
        public readonly array $_rawResponse = [],
    ) {}
}
