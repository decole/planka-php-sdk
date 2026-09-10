<?php

declare(strict_types=1);

namespace Planka\Bridge\Contracts\Resources;

use Planka\Bridge\Inputs\CustomFieldPatchInput;
use Planka\Bridge\Inputs\PatchInputInterface;
use Planka\Bridge\Views\Dto\CustomField\CustomFieldDto;

interface CustomFieldResourceInterface
{
    public function createInBaseGroup(string $baseGroupId, string $name, int $position = 65536, ?bool $showOnFrontOfCard = null): CustomFieldDto;

    public function createInGroup(string $groupId, string $name, int $position = 65536, ?bool $showOnFrontOfCard = null): CustomFieldDto;

    public function update(string $id, ?string $name = null, ?int $position = null, ?bool $showOnFrontOfCard = null): CustomFieldDto;

    /**
     * @param array{
     *   name?: string,
     *   position?: int,
     *   showOnFrontOfCard?: bool
     * }|CustomFieldPatchInput|PatchInputInterface $map
     */
    public function patching(string $id, array|PatchInputInterface $map): CustomFieldDto;

    public function delete(string $id): CustomFieldDto;
}
