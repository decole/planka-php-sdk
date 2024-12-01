<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\Attachment;

use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;
use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Exceptions\FileExistException;
use Planka\Bridge\Traits\AttachmentHydrateTrait;
use Planka\Bridge\Traits\AuthenticateTrait;
use Symfony\Component\Mime\Part\DataPart;

final class AttachmentCreateAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    use AuthenticateTrait;
    use AttachmentHydrateTrait;

    /**
     * @throws FileExistException
     */
    public function __construct(
        private readonly string $cardId,
        private readonly string $file,
        string $token,
    ) {
        $this->setToken($token);

        if (!file_exists($file) || !is_readable($file)) {
            throw new FileExistException("File not exist {$file}");
        }
    }

    public function url(): string
    {
        return "api/cards/{$this->cardId}/attachments";
    }

    public function getOptions(): array
    {
        $formFields = [
            'file' => DataPart::fromPath($this->file),
        ];
        $formData = new FormDataPart($formFields);

        return [
            'headers' => $formData->getPreparedHeaders()->toArray(),
            'body' => $formData->bodyToIterable(),
        ];
    }
}
