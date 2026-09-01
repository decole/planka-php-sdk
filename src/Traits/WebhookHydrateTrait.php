<?php

declare(strict_types=1);

namespace Planka\Bridge\Traits;

use Planka\Bridge\Exceptions\ResponseException;
use Planka\Bridge\Views\Dto\Webhook\WebhookDto;
use Planka\Bridge\Views\Factory\Webhook\WebhookDtoFactory;
use Symfony\Contracts\HttpClient\ResponseInterface;

trait WebhookHydrateTrait
{
    final public function hydrate(ResponseInterface $response): WebhookDto
    {
        $result = $response->toArray();

        if (array_key_exists('item', $result)) {
            return (new WebhookDtoFactory())->create($result['item']);
        }

        throw new ResponseException($response->getContent());
    }
}
