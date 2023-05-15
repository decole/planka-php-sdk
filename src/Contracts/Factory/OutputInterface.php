<?php

namespace Planka\Bridge\Contracts\Factory;

interface OutputInterface
{
    /** @param array $data */
    public function create(array $data): mixed;
}