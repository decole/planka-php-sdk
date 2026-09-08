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
     *      array{
     *          id: string,
     *          name: string,
     *          url: string,
     *          accessToken?: ?string,
     *          events?: ?list<string>,
     *          excludedEvents?: ?list<string>,
     *          createdAt?: ?string,
     *          updatedAt?: ?string
     *      }
     */
    public function create(array $data): WebhookDto
    {
        $data = $data['item'] ?? $data;

        return new WebhookDto(
            id: $data['id'],
            name: $data['name'],
            url: $data['url'],
            accessToken: $data['accessToken'] ?? null,
            events: $data['events'] ?? null,
            excludedEvents: $data['excludedEvents'] ?? null,
            boardId: $data['boardId'] ?? null,
            projectManagerId: $data['projectManagerId'] ?? null,
            createdAt: $this->convertToDateTime($data['createdAt'] ?? null),
            updatedAt: $this->convertToDateTime($data['updatedAt'] ?? null),
            _rawResponse: $data,
        );
    }
}
