<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory;

use Planka\Bridge\Contracts\Factory\OutputInterface;

final class ItemDtoListFactory implements OutputInterface
{
    public function __construct(private readonly OutputInterface $itemFactory) {}

    public function create(array $data): array
    {
        $items = $data['items'] ?? $data;

        return array_map(
            fn(array $item): mixed => $this->itemFactory->create($item),
            $items,
        );
    }
}
