<?php

namespace App\Services\Orders;

use App\Domain\Orders\Dto\PaginatedOrdersDTO;
use App\Domain\Orders\Interfaces\OrderRepositoryInterface;

class ListOrdersUseCase
{
    public function __construct(private OrderRepositoryInterface $order_repository) {}

    public function execute(int $page, int $limit): PaginatedOrdersDTO
    {
        return $this->order_repository->findAll($page, $limit);
    }
}
