<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\CustomField;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Factory\CustomField\BaseCustomFieldGroupDtoFactory;

final class BaseCustomFieldGroupDeleteAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    public function __construct(private readonly string $id) {}

    public function url(): string
    {
        return "api/base-custom-field-groups/{$this->id}";
    }

    public function getOptions(): array
    {
        return [];
    }

    public function getFactory(): OutputInterface
    {
        return new BaseCustomFieldGroupDtoFactory();
    }
}
