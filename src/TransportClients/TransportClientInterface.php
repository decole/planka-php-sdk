<?php

declare(strict_types=1);

namespace Planka\Bridge\TransportClients;

use Planka\Bridge\Contracts\Actions\ActionInterface;

interface TransportClientInterface
{
    public function get(ActionInterface $action): mixed;

    public function post(ActionInterface $action): mixed;

    public function patch(ActionInterface $action): mixed;

    public function delete(ActionInterface $action): mixed;
}
