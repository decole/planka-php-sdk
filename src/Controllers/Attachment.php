<?php

declare(strict_types=1);

namespace Planka\Bridge\Controllers;

use Planka\Bridge\Actions\Attachment\AttachmentCreateAction;
use Planka\Bridge\Actions\Attachment\AttachmentDeleteAction;
use Planka\Bridge\Actions\Attachment\AttachmentUpdateAction;
use Planka\Bridge\Actions\Common\CommonPatchAction;
use Planka\Bridge\Config;
use Planka\Bridge\Exceptions\FileExistException;
use Planka\Bridge\Traits\AttachmentHydrateTrait;
use Planka\Bridge\TransportClients\Client;
use Planka\Bridge\Views\Dto\Attachment\AttachmentDto;

final class Attachment
{
    use AttachmentHydrateTrait;

    public function __construct(
        private readonly Config $config,
        private readonly Client $client,
    ) {}

    /**
     * 'POST /api/cards/:cardId/attachments'.
     *
     * @throws FileExistException
     */
    public function upload(string $cardId, string $file): AttachmentDto
    {
        return $this->client->post(new AttachmentCreateAction(
            cardId: $cardId,
            file: $file,
            token: $this->config->getAuthToken(),
        ));
    }

    /** 'PATCH /api/attachments/:id' */
    public function updateName(string $attachmentId, string $name): AttachmentDto
    {
        return $this->client->patch(new AttachmentUpdateAction(
            attachmentId: $attachmentId,
            name: $name,
            token: $this->config->getAuthToken(),
        ));
    }

    /**
     * 'PATCH /api/attachments/:id' - Partially updates attachment properties.
     *
     * @param string $attachmentId Attachment ID
     * @param array{
     *   name?: string
     * } $map Associative array of fields to update
     */
    public function patching(string $attachmentId, array $map): AttachmentDto
    {
        return $this->client->patch(new CommonPatchAction(
            urlPath: "api/attachments/{$attachmentId}",
            data: $map,
            hydrateCallback: fn($response) => $this->hydrate($response),
        ));
    }

    /** 'DELETE /api/attachments/:id' */
    public function delete(string $attachmentId): AttachmentDto
    {
        return $this->client->delete(new AttachmentDeleteAction(
            attachmentId: $attachmentId,
            token: $this->config->getAuthToken(),
        ));
    }
}
