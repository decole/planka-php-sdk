<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\Auth;

use Symfony\Component\Mime\Part\Multipart\FormDataPart;
use Planka\Bridge\Contracts\Actions\ActionInterface;

final class AuthenticateAction implements ActionInterface
{
    private array $options = [];

    public function __construct(string $username, string $password)
    {
        $formFields = [
            'emailOrUsername' => $username,
            'password' => $password
        ];
        $formData = new FormDataPart($formFields);
        $this->options['headers'] = $formData->getPreparedHeaders()->toArray();
        $this->options['body'] = $formData->bodyToIterable();
    }

    public function url(): string
    {
        return 'api/access-tokens';
    }

    public function getOptions(): array
    {
        return $this->options;
    }
}