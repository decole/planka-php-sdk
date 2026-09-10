<?php

declare(strict_types=1);

namespace Planka\Bridge\Contracts\Resources;

use Planka\Bridge\Inputs\AttachmentPatchInput;
use Planka\Bridge\Inputs\PatchInputInterface;
use Planka\Bridge\Views\Dto\Attachment\AttachmentDto;

interface AttachmentResourceInterface
{
    public function upload(string $cardId, string $file): AttachmentDto;

    public function updateName(string $attachmentId, string $name): AttachmentDto;

    /**
     * @param array{
     *   name?: string
     * }|AttachmentPatchInput|PatchInputInterface $map
     */
    public function patching(string $attachmentId, array|PatchInputInterface $map): AttachmentDto;

    public function delete(string $attachmentId): AttachmentDto;
}
