<?php

namespace App\Domain\Orders\Dto;

readonly class UpdateOrderDTO
{
    public function __construct(
        public string $orderId,
        public string $product_name,
        public int $amount,
    ) {}
}
