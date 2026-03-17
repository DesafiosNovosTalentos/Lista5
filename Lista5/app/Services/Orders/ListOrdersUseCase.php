<?php

namespace App\Services\Orders;

use App\Domain\Orders\Interfaces\OrderRepositoryInterface;

class ListOrdersUseCase
{
    public function __construct(private OrderRepositoryInterface $order_repository) {}

    public function execute()
    {
        return $this->order_repository->findAll();
    }
}
