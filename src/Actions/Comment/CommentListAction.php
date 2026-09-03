<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\Comment;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Traits\AuthenticateTrait;
use Planka\Bridge\Views\Factory\Comment\CommentDtoFactory;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class CommentListAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    use AuthenticateTrait;

    public function __construct(
        private readonly string $cardId,
        string $token = '',
    ) {
        $this->setToken($token);
    }

    public function url(): string
    {
        return "api/cards/{$this->cardId}/comments";
    }

    public function getOptions(): array
    {
        return [];
    }

    public function hydrate(ResponseInterface $response): array
    {
        $result = $response->toArray();
        $items = $result['items'] ?? [];
        $factory = new CommentDtoFactory();

        return array_map(fn (array $item) => $factory->create($item), $items);
    }
}
