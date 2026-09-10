<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\Common;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Factory\Common\BootstrapDtoFactory;

final class GetBootstrapAction implements ActionInterface, ResponseResultInterface
{
    public function url(): string
    {
        return 'api/bootstrap';
    }

    public function getOptions(): array
    {
        return [];
    }

    public function getFactory(): OutputInterface
    {
        return new BootstrapDtoFactory();
    }
}
