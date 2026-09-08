<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\Attachment;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Factory\Attachment\AttachmentDtoFactory;

final class AttachmentUpdateAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    public function __construct(
        private readonly string $attachmentId,
        private readonly string $name,
    ) {}

    public function url(): string
    {
        return "api/attachments/{$this->attachmentId}";
    }

    public function getOptions(): array
    {
        return [
            'json' => [
                'name' => $this->name,
            ],
        ];
    }

    public function getFactory(): OutputInterface
    {
        return new AttachmentDtoFactory();
    }
}
