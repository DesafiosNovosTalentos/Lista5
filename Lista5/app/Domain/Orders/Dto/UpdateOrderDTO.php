<?php

namespace App\Domain\Orders\Dto;

readonly class UpdateOrderDTO
{
    public function __construct(
        public string $order_id,
        public string $product_name,
        public int $amount,
    ) {}
}
