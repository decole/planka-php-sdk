<?php

declare(strict_types=1);

namespace Planka\Bridge\Contracts\Resources;

use Planka\Bridge\Builders\BoardBuilder;
use Planka\Bridge\Inputs\BoardCreateInput;
use Planka\Bridge\Inputs\BoardPatchInput;
use Planka\Bridge\Inputs\PatchInputInterface;
use Planka\Bridge\Views\Dto\Board\BoardDto;
use Planka\Bridge\Views\Dto\Card\CardActionListDto;

interface BoardResourceInterface
{
    public function builder(?string $name = null): BoardBuilder;

    public function create(string $projectId, string|BoardCreateInput|BoardBuilder $nameOrInput, int $position = 65536): BoardDto;

    public function get(string $boardId): BoardDto;

    public function update(string $boardId, string $name): BoardDto;

    /**
     * @param array<string, mixed>|BoardPatchInput|PatchInputInterface $map
     */
    public function patching(string $boardId, array|PatchInputInterface $map): BoardDto;

    public function delete(string $boardId): BoardDto;

    public function getActions(string $boardId, ?string $beforeId = null): CardActionListDto;
}
