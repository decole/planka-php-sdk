<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\Webhook;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Views\Dto\Webhook\WebhookDto;
use Planka\Bridge\Views\Factory\Webhook\WebhookDtoFactory;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class WebhookListAction implements ActionInterface, ResponseResultInterface
{
    public function url(): string
    {
        return 'api/webhooks';
    }

    public function getOptions(): array
    {
        return [];
    }

    /**
     * @return list<WebhookDto>
     */
    public function hydrate(ResponseInterface $response): array
    {
        $result = $response->toArray();
        $factory = new WebhookDtoFactory();

        return array_map(
            static fn (array $item): WebhookDto => $factory->create($item),
            $result['items'] ?? [],
        );
    }
}
