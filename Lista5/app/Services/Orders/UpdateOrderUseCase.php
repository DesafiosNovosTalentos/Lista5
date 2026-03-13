<?php

namespace App\Services\Orders;

use App\Domain\Orders\Dto\UpdateOrderDTO;
use App\Domain\Orders\Entity\Order;
use App\Domain\Orders\Interfaces\OrderRepositoryInterface;
use App\Exceptions\EntityNotFoundException;

class UpdateOrderUseCase
{
    public function __construct(private OrderRepositoryInterface $order_repository) {}

    public function execute(UpdateOrderDTO $dto): Order
    {
        $order = $this->order_repository->findById($dto->orderId);

        if ($order === null) {
            throw new EntityNotFoundException('Pedido não encontrado.');
        }

        $order->update($dto->product_name, $dto->amount);

        return $this->order_repository->update($order);
    }
}
