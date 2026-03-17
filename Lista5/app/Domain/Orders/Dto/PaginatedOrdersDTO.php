<?php

namespace App\Domain\Orders\Dto;

readonly class PaginatedOrdersDTO
{
    public function __construct(
        public array $items,
        public int $total,
        public int $current_page,
        public int $per_page
    ) {}

    public function lastPage(): int
    {
        return (int) ceil($this->total / $this->per_page);
    }
}
