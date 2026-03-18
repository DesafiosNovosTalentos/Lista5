<?php

namespace App\Services\Orders;

use App\Domain\Orders\Entity\Order as DomainOrder;
use App\Domain\Orders\Interfaces\OrderRepositoryInterface;
use App\Exceptions\EntityNotFoundException;

class GetOrderUseCase
{
    public function __construct(private OrderRepositoryInterface $order_repository) {}

    public function execute(string $order_id): DomainOrder
    {
        $order = $this->order_repository->findById($order_id);

        if ($order === null) {
            throw new EntityNotFoundException('Pedido não encontrado.');
        }

        return $order;
    }
}
