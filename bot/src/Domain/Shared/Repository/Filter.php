<?php
declare(strict_types=1);

namespace Bot\Domain\Shared\Repository;

class Filter
{

    public function __construct(
        public readonly string $field,
        public readonly string $condition,
        public readonly string|bool $value,
        public bool $hasMultipleValues = false,
        public readonly array $values = []
    )
    {
    }
}