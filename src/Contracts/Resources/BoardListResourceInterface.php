<?php

declare(strict_types=1);

namespace Planka\Bridge\Contracts\Resources;

use Planka\Bridge\Builders\BoardListBuilder;
use Planka\Bridge\Enum\ListTypeEnum;
use Planka\Bridge\Inputs\BoardListPatchInput;
use Planka\Bridge\Inputs\PatchInputInterface;
use Planka\Bridge\Views\Dto\Board\BoardListDto;

interface BoardListResourceInterface
{
    public function builder(?string $name = null): BoardListBuilder;

    public function create(
        string $boardId,
        string $name,
        int $position,
        ListTypeEnum $type = ListTypeEnum::ACTIVE,
    ): BoardListDto;

    public function update(string $listId, string $name): BoardListDto;

    /**
     * @param array{
     *   name?: string,
     *   position?: int,
     *   type?: 'active'|'closed'|'archive'|'trash',
     *   color?: string|null
     * }|BoardListPatchInput|PatchInputInterface $map
     */
    public function patching(string $listId, array|PatchInputInterface $map): BoardListDto;

    public function delete(string $listId): BoardListDto;

    public function clear(string $listId): BoardListDto;

    public function moveCards(string $sourceListId, string $targetListId): BoardListDto;

    public function sort(string $listId, string $fieldName, string $order = 'asc'): BoardListDto;
}
