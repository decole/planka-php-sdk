<?php

declare(strict_types=1);

namespace Planka\Bridge\Pagination;

/**
 * Lazy, memory-efficient paginator using PHP Generators.
 *
 * @template T
 *
 * @implements \IteratorAggregate<int, T>
 */
final class Paginator implements \IteratorAggregate, \Countable
{
    /**
     * @param callable(?string): array{items: list<T>, nextCursor?: ?string} $pageFetcher
     */
    public function __construct(
        private readonly mixed $pageFetcher,
        private readonly ?string $initialCursor = null,
    ) {}

    /**
     * Iterates lazily over all items across all pages.
     *
     * @return \Generator<int, T>
     */
    public function getIterator(): \Generator
    {
        $cursor = $this->initialCursor;

        while (true) {
            $fetcher = $this->pageFetcher;
            /** @var array{items: list<T>, nextCursor?: ?string} $result */
            $result = $fetcher($cursor);

            $items = $result['items'] ?? [];
            $nextCursor = $result['nextCursor'] ?? null;

            foreach ($items as $item) {
                yield $item;
            }

            if (empty($items) || null === $nextCursor || $nextCursor === $cursor) {
                break;
            }

            $cursor = $nextCursor;
        }
    }

    /**
     * Collects all paginated items into a single array.
     *
     * @return list<T>
     */
    public function toArray(): array
    {
        return iterator_to_array($this->getIterator(), false);
    }

    /**
     * Counts total items by iterating through generator.
     */
    public function count(): int
    {
        return iterator_count($this->getIterator());
    }
}
