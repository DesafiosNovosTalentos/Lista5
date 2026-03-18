<?php

namespace App\Services\Orders;

use App\Domain\Orders\Interfaces\OrderRepositoryInterface;
use App\Exceptions\EntityNotFoundException;

class DeleteOrderUseCase
{
    public function __construct(private OrderRepositoryInterface $order_repository) {}

    public function execute(string $order_id): void
    {
        $order = $this->order_repository->findById($order_id);

        if ($order === null) {
            throw new EntityNotFoundException('Pedido não encontrado.');
        }

        $this->order_repository->delete($order_id);
    }
}
