<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\SystemConfig;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Factory\SystemConfig\SystemConfigDtoFactory;

final class SystemConfigUpdateAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    private array $options = [];

    public function __construct(array $data)
    {
        $this->options['json'] = $data;
    }

    public function url(): string
    {
        return 'api/system-settings';
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getFactory(): OutputInterface
    {
        return new SystemConfigDtoFactory();
    }
}
