<?php

declare(strict_types=1);

namespace Planka\Bridge\Contracts\Resources;

use Planka\Bridge\Views\Dto\Card\CardLabelDto;

interface CardLabelResourceInterface
{
    public function add(string $cardId, string $labelId): CardLabelDto;

    public function remove(string $cardId, string $labelId): CardLabelDto;
}
