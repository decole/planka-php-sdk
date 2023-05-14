<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\Notification;

use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Planka\Bridge\Views\Factory\Notification\NotificationItemDtoFactory;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Planka\Bridge\Views\Dto\Notification\NotificationItemDto;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ActionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Planka\Bridge\Traits\AuthenticateTrait;
use function Fp\Collection\map;

final class NotificationUpdateAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    use AuthenticateTrait;

    public function __construct(
        string $token,
        private readonly array $notifyIdList,
        private readonly bool $isRead
    ) {
        $this->setToken($token);
    }

    public function url(): string
    {
        $list = implode(',', $this->notifyIdList);

        return "api/notifications/{$list}";
    }

    public function getOptions(): array
    {
        return [
            'body' => [
                'isRead' => $this->isRead,
            ],
        ];
    }

    /**
     * @return list<NotificationItemDto>
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function hydrate(ResponseInterface $response): array
    {
        $data = $response->toArray();

        return map($data['items'] ?? [], fn(array $item) => (new NotificationItemDtoFactory())->create($item));
    }
}