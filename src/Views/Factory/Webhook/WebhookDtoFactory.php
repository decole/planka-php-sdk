<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\Webhook;

use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Traits\DateConverterTrait;
use Planka\Bridge\Views\Dto\Webhook\WebhookDto;

final class WebhookDtoFactory implements OutputInterface
{
    use DateConverterTrait;

    /**
     * @param array<string, mixed> $data
     *
     * @see Payload structure:
     * array{
     *     id: string,
     *     name: string,
     *     url: string,
     *     accessToken?: ?string,
     *     events?: ?list<string>,
     *     excludedEvents?: ?list<string>,
     *     createdAt?: ?string,
     *     updatedAt?: ?string
     * }
     */
    public function create(array $data): WebhookDto
    {
        $data = $data['item'] ?? $data;

        return new WebhookDto(
            id: (string) $data['id'],
            name: (string) $data['name'],
            url: (string) $data['url'],
            accessToken: isset($data['accessToken']) && is_string($data['accessToken']) ? $data['accessToken'] : null,
            events: isset($data['events']) && is_array($data['events']) ? $data['events'] : null,
            excludedEvents: isset($data['excludedEvents']) && is_array($data['excludedEvents']) ? $data['excludedEvents'] : null,
            boardId: isset($data['boardId']) && is_string($data['boardId']) ? $data['boardId'] : null,
            projectManagerId: isset($data['projectManagerId']) && is_string($data['projectManagerId']) ? $data['projectManagerId'] : null,
            createdAt: $this->convertToDateTime($data['createdAt'] ?? null),
            updatedAt: $this->convertToDateTime($data['updatedAt'] ?? null),
            _rawResponse: $data,
        );
    }
}
