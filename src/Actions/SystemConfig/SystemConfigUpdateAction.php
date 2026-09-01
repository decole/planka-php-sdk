<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\SystemConfig;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Traits\SystemConfigHydrateTrait;

final class SystemConfigUpdateAction implements ActionInterface, ResponseResultInterface
{
    use SystemConfigHydrateTrait;

    private array $options = [];

    public function __construct(array $configData)
    {
        $this->options['json'] = $configData;
    }

    public function url(): string
    {
        return 'api/config';
    }

    public function getOptions(): array
    {
        return $this->options;
    }
}
