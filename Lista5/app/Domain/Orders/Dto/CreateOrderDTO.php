<?php

namespace App\Domain\Orders\Dto;

class CreateOrderDTO
{
    public function __construct(
        public readonly string $user_id,
        public readonly string $product_name,
        public readonly int $amount,
    ) {}
}
