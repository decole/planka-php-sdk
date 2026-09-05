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
     * @param array{
     *     id: string,
     *     name: string,
     *     url: string,
     *     accessToken: ?string,
     *     events: ?list<string>,
     *     excludedEvents: ?list<string>,
     *     createdAt: ?string,
     *     updatedAt: ?string
     * } $data
     */
    public function create(array $data): WebhookDto
    {
        return new WebhookDto(
            id: $data['id'],
            name: $data['name'],
            url: $data['url'],
            accessToken: $data['accessToken'] ?? null,
            events: $data['events'] ?? null,
            excludedEvents: $data['excludedEvents'] ?? null,
            createdAt: $this->convertToDateTime($data['createdAt'] ?? null),
            updatedAt: $this->convertToDateTime($data['updatedAt'] ?? null),
            _rawResponse: $data,
        );
    }
}
