<?php

declare(strict_types=1);

namespace Planka\Bridge\Contracts\Resources;

use Planka\Bridge\Enum\LabelColorEnum;
use Planka\Bridge\Views\Dto\Label\LabelDto;

interface LabelResourceInterface
{
    public function create(string $boardId, string $name, LabelColorEnum $color, int $position): LabelDto;

    public function update(string $labelId, string $name, LabelColorEnum $color): LabelDto;

    /**
     * @param array{name?: string|null, color?: string|LabelColorEnum, position?: int} $map
     */
    public function patching(string $labelId, array $map): LabelDto;

    public function delete(string $labelId): LabelDto;
}
