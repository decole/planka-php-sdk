<?php

declare(strict_types=1);

namespace Planka\Bridge\Contracts\Resources;

use Planka\Bridge\Inputs\CustomFieldGroupPatchInput;
use Planka\Bridge\Inputs\PatchInputInterface;
use Planka\Bridge\Views\Dto\CustomField\CustomFieldGroupDto;

interface CustomFieldGroupResourceInterface
{
    public function createInBoard(
        string $boardId,
        ?string $name = null,
        ?string $baseCustomFieldGroupId = null,
        int $position = 65536,
    ): CustomFieldGroupDto;

    public function createInCard(
        string $cardId,
        ?string $name = null,
        ?string $baseCustomFieldGroupId = null,
        int $position = 65536,
    ): CustomFieldGroupDto;

    public function update(string $id, ?string $name = null, ?int $position = null): CustomFieldGroupDto;

    /**
     * @param array{
     *   name?: string|null,
     *   position?: int
     * }|CustomFieldGroupPatchInput|PatchInputInterface $map
     */
    public function patching(string $id, array|PatchInputInterface $map): CustomFieldGroupDto;

    public function delete(string $id): CustomFieldGroupDto;
}
