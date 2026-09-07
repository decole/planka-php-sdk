<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\Attachment;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Factory\Attachment\AttachmentDtoFactory;

final class AttachmentDeleteAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    public function __construct(private readonly string $attachmentId) {}

    public function url(): string
    {
        return "api/attachments/{$this->attachmentId}";
    }

    public function getOptions(): array
    {
        return [];
    }

    public function getFactory(): OutputInterface
    {
        return new AttachmentDtoFactory();
    }
}
