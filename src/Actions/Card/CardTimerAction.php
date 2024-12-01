<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\Card;

use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Views\Dto\Card\StopWatchDto;
use Planka\Bridge\Traits\AuthenticateTrait;
use Planka\Bridge\Traits\CardHydrateTrait;
use Planka\Bridge\Views\Dto\Card\CardDto;

final class CardTimerAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    use AuthenticateTrait;
    use CardHydrateTrait;

    private bool $start;

    public function __construct(private readonly CardDto $card, string $token, bool $start)
    {
        $this->start = $start;
        $this->setToken($token);
    }

    public function url(): string
    {
        return "api/cards/{$this->card->id}";
    }

    public function getOptions(): array
    {
        $startedAt = null;
        $total = 0;

        if ($this->start) {
            $stopwatch = $this->tickingWatch();
            $startedAt = $stopwatch->startedAt->format("Y-m-d\TH:i:s.v\Z");
        }

        // stop timer
        if (!$this->start) {
            if (null !== $this->card->stopwatch) {
                $diff = time() - $this->card->stopwatch->startedAt->getTimestamp();
                $total = $this->card->stopwatch->total + $diff;

                $stopwatch = new StopWatchDto(null, $total);

                $this->card->stopwatch = $stopwatch;
            }
        }

        return [
            'json' => [
                'stopwatch' => [
                    'startedAt' => $startedAt,
                    'total' => $total,
                ],
            ],
        ];
    }

    private function tickingWatch(): StopWatchDto
    {
        // pause condition
        if ($this->card->stopwatch) {
            $diff = $this->card->stopwatch->total;

            return new StopWatchDto((new \DateTimeImmutable())->modify("-{$diff} seconds"), 0);
        }

        return new StopWatchDto(new \DateTimeImmutable(), 0);
    }
}
