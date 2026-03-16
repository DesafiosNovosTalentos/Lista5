<?php

namespace App\Domain\Orders\Dto;

readonly class CreateOrderDTO
{
    public function __construct(
        public string $user_id,
        public string $product_name,
        public int $amount,
    ) {}
}
