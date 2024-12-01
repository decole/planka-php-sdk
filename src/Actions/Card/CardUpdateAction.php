<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\Card;

use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Traits\AuthenticateTrait;
use Planka\Bridge\Traits\CardHydrateTrait;
use Planka\Bridge\Views\Dto\Card\CardDto;

final class CardUpdateAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    use AuthenticateTrait;
    use CardHydrateTrait;

    public function __construct(
        private readonly CardDto $card,
        string $token,
        private readonly ?int $spentSeconds = null,
    ) {
        $this->setToken($token);
    }

    public function url(): string
    {
        return "api/cards/{$this->card->id}";
    }

    public function getOptions(): array
    {
        if (null !== $this->spentSeconds) {
            return [
                'json' => [
                    'stopwatch' => [
                        'startedAt' => null,
                        'total' => $this->getTotalTime(),
                    ],
                ],
            ];
        }

        $body = [
            'json' => [
                'name' => $this->card->name,
                'description' => $this->card->description,
                'dueDate' => $this->card?->dueDate?->format('Y-m-d\TH:i:s.v\Z'),
                'listId' => $this->card->listId,
                'position' => $this->card->position,
            ],
        ];

        if (null === $this->card->stopwatch) {
            $body['json']['stopwatch'] = null;
        }

        return $body;
    }

    private function getTotalTime(): int
    {
        $time = $this->card?->stopwatch->total ?? 0;

        return $time + $this->spentSeconds;
    }
}
