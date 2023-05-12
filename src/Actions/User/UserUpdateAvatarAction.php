<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\User;

use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;
use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Exceptions\FileExistException;
use Planka\Bridge\Traits\AuthenticateTrait;
use Planka\Bridge\Traits\UserHydrateTrait;
use Symfony\Component\Mime\Part\DataPart;
use Planka\Bridge\Views\Dto\User\UserDto;

final class UserUpdateAvatarAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    use AuthenticateTrait, UserHydrateTrait;

    /**
     * @throws FileExistException
     */
    public function __construct(
        string $token,
        private readonly UserDto $user,
        private readonly string $file
    ) {
        $this->setToken($token);

        if (!file_exists($file) || !is_readable($file)) {
            throw new FileExistException("File not exist {$file}");
        }
    }

    public function url(): string
    {
        return "api/users/{$this->user->id}/avatar";
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