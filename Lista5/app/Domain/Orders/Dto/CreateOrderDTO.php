<?php

namespace App\Domain\Orders\Dto;

class CreateOrderDTO
{
    public function __construct(
        public readonly string $userId,
        public readonly string $productName,
        public readonly int $amount,
    ) {}
}
