<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\CardAction;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Traits\AuthenticateTrait;
use Planka\Bridge\Views\Dto\Card\CardActionListDto;
use Planka\Bridge\Views\Factory\Card\CardActionListDtoFactory;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class BoardActionListAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    use AuthenticateTrait;

    public function __construct(
        private readonly string $boardId,
        private readonly ?string $beforeId = null,
        string $token = '',
    ) {
        $this->setToken($token);
    }

    public function url(): string
    {
        return "api/boards/{$this->boardId}/actions";
    }

    public function getOptions(): array
    {
        if (null !== $this->beforeId) {
            return [
                'query' => [
                    'beforeId' => $this->beforeId,
                ],
            ];
        }

        return [];
    }

    public function hydrate(ResponseInterface $response): CardActionListDto
    {
        return (new CardActionListDtoFactory())->create($response->toArray());
    }
}
