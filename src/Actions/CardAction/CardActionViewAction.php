<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\CardAction;

use Planka\Bridge\Views\Factory\Card\CardActionListDtoFactory;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ActionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Planka\Bridge\Views\Dto\Card\CardActionListDto;
use Planka\Bridge\Traits\AuthenticateTrait;

final class CardActionViewAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    use AuthenticateTrait;

    public function __construct(private readonly string $cardId, string $token)
    {
        $this->setToken($token);
    }

    public function url(): string
    {
        return "api/cards/{$this->cardId}/actions";
    }

    public function getOptions(): array
    {
        return [
            'body' => [
                'withDetails' => false,
            ],
        ];
    }

    public function hydrate(ResponseInterface $response): CardActionListDto
    {
        return (new CardActionListDtoFactory())->create($response->toArray());
    }
}