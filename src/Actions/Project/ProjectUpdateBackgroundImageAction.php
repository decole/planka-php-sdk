<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\Project;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Exceptions\FileExistException;
use Planka\Bridge\Views\Factory\Project\ProjectDtoFactory;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;

final class ProjectUpdateBackgroundImageAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    /**
     * @throws FileExistException
     */
    public function __construct(
        private readonly string $projectId,
        private readonly string $file,
    ) {
        if (!file_exists($file) || !is_readable($file)) {
            throw new FileExistException("File not exist {$file}");
        }
    }

    public function url(): string
    {
        return "api/projects/{$this->projectId}/background-image";
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

    public function getFactory(): OutputInterface
    {
        return new ProjectDtoFactory();
    }
}
