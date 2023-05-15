<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\Attachment;

use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Traits\AttachmentHydrateTrait;
use Planka\Bridge\Traits\AuthenticateTrait;

final class AttachmentUpdateAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    use AuthenticateTrait, AttachmentHydrateTrait;

    public function __construct(
        private readonly string $attachmentId,
        private readonly string $name,
        string $token
    ) {
        $this->setToken($token);
    }

    public function url(): string
    {
        return "api/attachments/{$this->attachmentId}";
    }

    public function getOptions(): array
    {
        return [
            'body' => [
                'name' => $this->name,
            ],
        ];
    }
}