<?php

declare(strict_types=1);

namespace Planka\Bridge\Contracts\Actions;

use Planka\Bridge\Contracts\Factory\OutputInterface;

interface ResponseResultInterface
{
    public function getFactory(): OutputInterface|callable;
}
