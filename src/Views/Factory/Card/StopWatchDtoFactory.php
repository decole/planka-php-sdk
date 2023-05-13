<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\Card;

use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Dto\Card\StopWatchDto;
use Planka\Bridge\Traits\DateConverterTrait;

final class StopWatchDtoFactory implements OutputInterface
{
    use DateConverterTrait;

    public function create(?array $data): ?StopWatchDto
    {
        if (empty($data)) {
            return null;
        }

        return new StopWatchDto(
            startedAt: $this->convertToDateTime($data['startedAt']),
            total: (int)$data['total']
        );
    }
}