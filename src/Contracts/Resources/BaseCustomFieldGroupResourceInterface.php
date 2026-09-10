<?php

declare(strict_types=1);

namespace Planka\Bridge\Contracts\Resources;

use Planka\Bridge\Inputs\BaseCustomFieldGroupPatchInput;
use Planka\Bridge\Inputs\PatchInputInterface;
use Planka\Bridge\Views\Dto\CustomField\BaseCustomFieldGroupDto;

interface BaseCustomFieldGroupResourceInterface
{
    public function create(string $projectId, string $name): BaseCustomFieldGroupDto;

    public function update(string $id, string $name): BaseCustomFieldGroupDto;

    /**
     * @param array{
     *   name?: string
     * }|BaseCustomFieldGroupPatchInput|PatchInputInterface $map
     */
    public function patching(string $id, array|PatchInputInterface $map): BaseCustomFieldGroupDto;

    public function delete(string $id): BaseCustomFieldGroupDto;
}
